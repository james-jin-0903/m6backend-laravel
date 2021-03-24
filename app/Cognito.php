<?php
namespace App;
use App\CognitoJWT;

/**
* Class that interacts with aws cognito
* used as a model
*/
class Cognito {
    /**
     * creates an account for the user in aws cognito
     *
     * @param array $userParams
     * @return \Illuminate\Http\JsonResponse
     */
    public static function signup(array $userParams) {
        try {
            $payload = [
                'ClientId' => getenv('COGNITO_CLIENT_ID'),
                'Username' => $userParams["email"],
                'Password' => $userParams["password"],
                'UserAttributes' => [
                    [
                        'Name'  => 'name',
                        'Value' => $userParams["name"]
                    ],
                    [
                        'Name'  => 'family_name',
                        'Value' => $userParams["lastName"]
                    ],
                    [
                        'Name'  => 'email',
                        'Value' => $userParams["email"]
                    ],
                    [
                        'Name'  => 'custom:dynamoID',
                        'Value' => $userParams["dynamoID"]
                    ]
                ],
            ];

            return app()->make('CognitoSingleton')->signUp($payload);
        } catch( \Exception $e ) {
            return response()->json( Self::returnCognitoError($e) );
        }
    }

    /**
     * signs in user to cognito and returns the token
     *
     * @param array $userData
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public static function signin($userData){
        try {
            $result = app()->make('CognitoSingleton')->adminInitiateAuth([
                'AuthFlow'       => 'ADMIN_USER_PASSWORD_AUTH',
                'ClientId'       => getenv('COGNITO_CLIENT_ID'),
                'UserPoolId'     => getenv('COGNITO_USER_POOL_ID'),
                'AuthParameters' => [
                    'USERNAME'   => $userData["email"],
                    'PASSWORD'   => $userData["password"],
                ],
            ]);
            // if AuthResult exists it's returns
            // else an associative array with the type, and error messages
            // did it like this, because for some reason cognito
            // returns the error with a 200 status
            // so this was the only way to retun the error
            // DO NOT DELETE

            if( isset( $result["AuthenticationResult"] ) ) {
                $orig = (array) CognitoJWT::verifyToken($result["AuthenticationResult"]["AccessToken"]);
                return [
                    'AccessToken'  => $result["AuthenticationResult"]["AccessToken"],
                    'IdToken'      => $result["AuthenticationResult"]["IdToken"],
                    // TODO: the $orig['exp'] key will be missing if the verifyToken request fails.
                    'exp'          => $orig['exp'],
                ];
            } else {
                return response()->json( Self::returnCognitoError($result), 400 );
            }
        } catch ( \Exception $e) {
            // put this here in case aws ever fixes the above error
            // and actually returns an error with the correct status

            return response()->json( Self::returnCognitoError($e), 422 );
        }
    }
    /**
    * Confirms signup with a code provided by aws
    * @param Array $userData
    */
    public static function confirmSignupWithCode( $userData ) {
        try {
            $res = app()->make('CognitoSingleton')->confirmSignUp([
                'ClientId'         => getenv('COGNITO_CLIENT_ID'),
                'Username'         => $userData["email"],
                'ConfirmationCode' => $userData["code"],
            ]);

            /**
            * Same issue as above
            * so please don't change this unless you know what your doing
            */
            if( isset($res["@metadata"]) ) {
                return $res["@metadata"];
            } else {
                return response()->json( Self::returnCognitoError($res), 422 );
            }

        } catch (\Exception $e) {
            return response()->json( Self::returnCognitoError($e), 422 );
        }
    }
    /**
    ** resends confirmation code if the user hasn't
    * @param String $email
    */
    public static function resendConfirmationCode( $email ) {
        /**
         * tried to break this operation, but it even successfully sends
         * an confirmation codes to email addresses that haven't
         * been signup like something@email.com
         */
        try{
            return app()->make('CognitoSingleton')->resendConfirmationCode([
                'ClientId' => getenv('COGNITO_CLIENT_ID'),
                'Username' => $email,
            ]);
        } catch(\Exception $e) {
            // return response()->json( Self::returnCognitoError($e) );
            return response()->json( Self::returnCognitoError($e), 422 );
        }
    }
    /**
     * resetting password
     * @param String $email
     */
    public static function initiatePasswordReset($email) {
        try {
            return app()->make('CognitoSingleton')->forgotPassword([
                'ClientId' => getenv('COGNITO_CLIENT_ID'),
                'Username' => $email
            ]);
        } catch( \Exception $e ) {
            return response()->json( Self::returnCognitoError($e), 422 );
        }
    }
    /**
     * reset password - confirm password forgot
     * @param Array $confirmParams
     */
    public static function confirmPasswordReset( $confirmParams ) {
        try {
            $res = app()->make('CognitoSingleton')->confirmForgotPassword([
                'ClientId' => getenv('COGNITO_CLIENT_ID'),
                'ConfirmationCode' => $confirmParams["code"],
                'Password' => $confirmParams["password"],
                'Username' => $confirmParams["email"]
            ]);

            return $res;
        } catch( \Exception $e ) {
            return response()->json( Self::returnCognitoError($e), 422 );
        }
    }
    /**
     * Start password reset - sends confirmation code
     * @param String $email
     */
    public static function startPasswordReset($email) {
        try {
            $res = app()->make('CognitoSingleton')->forgotPassword([
                'ClientId' => getenv('COGNITO_CLIENT_ID'),
                'Username' => $email
            ]);

            return $res["CodeDeliveryDetails"];
        } catch( \Exception $e ) {
            return response()->json( Self::returnCognitoError($e), 422 );
        }
    }
    /**
    * handles the creation of the error associative array
    * from operations done with aws cognito
    * @return Array
    */
    private static function returnCognitoError($e) {
        if( method_exists ( $e , 'getAwsErrorCode' ) ) {
            return [
                'type'    => $e->getAwsErrorCode(),
                'msg'     => $e->getAwsErrorMessage(),
            ];
        } else {
            return [
                'type' => 'GENERIC_AUTH_ERR',
                'msg'  => $e->getMessage()
            ];
        }

    }
}
