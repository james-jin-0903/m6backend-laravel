<?php
namespace App\Http\Controllers;

use App\AppSync\UserDynamo;
use Illuminate\Http\Request;
use App\CognitoJWT;

class UserController extends Controller {

    protected function getUserByToken($token) {
      $userId = app()->make('CognitoSingleton')->getUser([
          'AccessToken' => $token
      ]);
      return $userId->get('Username');
    }
    /**
     * gets an array of ids
     * @param Request $request
     */
    public function index(Request $request) {
        try {
            $this->validate($request, [
                'userIds' => 'required'
            ]);

            [ 'userIds' => $userIds] = $request->only('userIds');

            $res = [];

            for( $x = 0; $x < count($userIds); $x++ ) {
                $userDynamo = new UserDynamo();
                $client = $userDynamo->getUserById($userIds[$x]);
                $rawJSON = $client->sendRequest();
                $json = $rawJSON["data"]["getUser"];
                array_push($res, $json);
            }

            return $res;
        } catch(\Exception $e) {
            return $e;
        }
    }
    /**
     * gets the user's individual data from dynamo
     *
     * @param Request $request
     */
    public function getUser(Request $request) {

      try{
        $this->validate($request, [
          'IdToken'  => 'required',
          'AccessToken'  => 'required'
        ]);

        [ 'IdToken' => $IdToken, 'AccessToken' => $AccessToken ] = $request->only('IdToken', 'AccessToken');

            $userDynamo = new UserDynamo();

            $client = $userDynamo->getUserByIdToken($IdToken);
            $rawJSON = $client->sendRequest();
            $json = $rawJSON["data"]["getUser"];


            // sets last visit
            $json["lastVisit"] = date("Y-m-d\TH:i:s\Z", time());
            $json["id"] = $this->getUserByToken($AccessToken);

            $updateJSON = $json;
            unset($updateJSON["createdAt"]);
            unset($updateJSON["updatedAt"]);
            unset($updateJSON["companies"]);

            $client = $userDynamo->update($updateJSON);
            $client->sendRequest();

            return $json;
        } catch( \Exception $e ) {
            return $this->checkForValidationErr( $e->errors() );
        }
    }
    public function getUsers(Request $request){
        $client = new UserDynamo();
        $data = $client->getUserByEmail($request->email);

        return response()->json([
            'message' => 'Successful',
            'users' => $data['data']['findUserByEmail']['items']
        ], 200);
    }

    public function getUsersByCompany(Request $request){
        $client = new UserDynamo();
        $data = $client->getUserByEmail($request->email);

        return response()->json([
            'message' => 'Successful',
            'users' => $data['data']['findUserByEmail']['items']
        ], 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'user' => 'required'
        ]);

        [ 'user' => $user ] = $request->only('user');

        unset($user["companies"]);
        unset($user["createdAt"]);
        unset($user["updatedAt"]);

        $userDynamo = new UserDynamo();
        $client = $userDynamo->update($user);
        $json = $client->sendRequest();

        return $json["data"]["updateUser"];
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
}
