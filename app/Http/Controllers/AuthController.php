<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cognito;
use App\AppSync\CompanyDynamo;
use App\AppSync\UserDynamo;
use App\AppSync\UserCompanyDynamo;
use Ramsey\Uuid\Uuid;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class AuthController extends Controller {
    /**
    * signup user, can create a new company, creates record in intermediary table to allow for connection
    * cognito user, and finally the user registry in the users table
    *
    * @param  Request  $request
    */
    public function signup(Request $request) {
        try{
            $dynamoID = Uuid::uuid4();

            $this->validate($request, [
                'firstName' => 'required',
                'lastName'  => 'required',
                'email'     => 'required|email',
                'company'   => 'required',
                'password'  => 'required|min:6'
            ]);

            $params = $request->only( 'firstName', 'lastName', 'email', 'company', 'password' );

            $isNewCompany = is_string($params["company"]);
            if ( $isNewCompany ) {
                /*
                * create a new company
                * this is only temporarily here as this will
                * probably have to be moved to somewhere else
                */
                $companyDynamo = new CompanyDynamo();

                $newCompany = [ 'name' => $params["company"] ];
                $client = $companyDynamo->createCompany($newCompany);
                $json = $client->sendRequest();
                $params["company"] = $json["data"]["createCompany"];
            }
            /*
            * create a connection between the company and the user
            * using the intermediary table
            */
            $userCompanyDynamo = new UserCompanyDynamo();
            $userCompanyData = [ 'userID' => $dynamoID, 'companyID' => $params["company"]["id"] ];
            if ($isNewCompany) {
                $userCompanyData['joinStatus'] = 'ACTIVE';
            }
            $client = $userCompanyDynamo->create($userCompanyData, true);
            $json = $client->sendRequest();

            /*
            * create the user in cognito
            */
            $userParams = [
                'name'     => $params["firstName"],
                'lastName' => $params["lastName"],
                'email'    => $params["email"],
                'password' => $params["password"],
                'dynamoID' => $dynamoID
            ];
            Cognito::signup( $userParams );
            /*
            * create the user in dynamo
            */
            $userDynamo = new UserDynamo();
            $client = $userDynamo->create($userParams);

            $json = $client->sendRequest();

            return $json["data"]["createUser"];
            /**
             * finish transfering all of the screens and done on monday
             * goal by lunch monday
             */
        } catch(\Expectation $e) {
            return $this->checkForValidationErr( $e->errors() );
        }
    }

    /**
     * Signs in the user
     *
     * @param  Request  $request
     */
    public function signin(Request $request) {
        try {
            $this->validate( $request, [
                'email'     => 'required|email',
                'password'  => 'required'
            ]);

            $res = (array) Cognito::signin( $request->only( 'email', 'password' ) );

            if( isset($res["original"]) ) {
                return response()->json($res["original"], 400);
            }
            return response()->json($res);
        } catch( \Exception $e ) {

            return response()->json(
                $this->checkForValidationErr( $e->errors() ),
                500
            );
        }
    }
    /**
    * confirms the users email address through a code aws cognito sends to the user
    * @param  Request  $request
    */
    public function confirmSignup(Request $request) {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'code' => 'required'
            ]);

            Cognito::confirmSignupWithCode( $request->only( 'email', 'code' ) );

            return response()->json([
                'msg'  => 'User email confirmed',
                'type' => 'USER_CONFIRM_SUCCESS'
            ]);
        } catch( \Exception $e) {
            return response()->json(
                $this->checkForValidationErr( $e->errors() ),
                500
            );
        }
    }
    /**
    * sends a new confirmation code to the users email, in case they try to login, but
    * they haven't confirmed their email yet
    * @param  Request  $request
    */
    public function resendConfirmCode(Request $request) {
        try {
            $this->validate($request, [
                'email' => 'required|email'
            ]);

            [ 'email' => $email ] =$request->only('email');
            /**
             * doesn't send an error even sending an email that has never been used
             * in this user pool
            */
            return Cognito::resendConfirmationCode( $email );
        } catch( \Exception $e ) {
            return response()->json(
                $this->checkForValidationErr( $e->errors() ),
                500
            );
        }
    }
    /**
     * confirms password reset with code sent by aws cognito
     * finish this tomorrow and finish up the frontend
     */
    public function startPasswordReset(Request $request) {
        try {
            $this->validate($request, [
                'email' => 'required|email',
            ]);

            [ 'email' => $email ] =$request->only('email');

            return Cognito::startPasswordReset( $email );
        } catch (\Exception $e) {
            return response()->json(
                $this->checkForValidationErr( $e->errors() ),
                500
            );
        }
    }
    /**
     * confirms password reset using a code that was emailed to the user
     *
     * @param Request $request
     */
    public function confirmPasswordReset(Request $request) {
        try{
            $this->validate($request, [
                'password' => 'required|min:6',
                'email'    => 'required|email',
                'code'     => 'required|min:6'
            ]);
            Cognito::confirmPasswordReset( $request->only( 'password', 'email', 'code' ) );

            return response()->json( $this->genericSuccessMsg() );
        } catch( \Exception $e ) {
            return response()->json(
                $this->checkForValidationErr( $e->errors() ),
                500
            );
        }
    }

    private function checkForValidationErr($e) {
        if( isset( $e["awsErr"] ) ) {
            unset( $e["awsErr"] );
            return $e;
        } else {
            return [
                'type' => 'INVALID_PARAMS',
                'msg'  => 'Please check the parameters',
            ];
        }
    }

    private function genericSuccessMsg() {
        return [
            'type' => 'SUCCESS',
            'msg' => 'The operation was carried out'
        ];
    }
}
