<?php
namespace App\Http\Controllers;
use App\GSChatController as GS;
use App\GSFeedController as GSFeed;
use Illuminate\Http\Request;
use App\CognitoJWT;

class GSController extends Controller {

    protected function getUser($token) {
        $userId = app()->make('CognitoSingleton')->getUser([
           'AccessToken' => $token
        ]);
        return $userId->get('Username');
    }

    public function getToken (Request $request) {
        try {

            $gs = new GS();
            $token = $gs->client->createToken($request->id);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Successful',
            'token' => $token
        ], 200);
    }

    public function getFeedToken (Request $request) {
        try {

            $gs = new GSFeed();
            $token = $gs->client->createUserSessionToken($request->id);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Successful',
            'token' => $token
        ], 200);
    }

    public function getFeedActivity ( $feedId, $room ) {
      try {
        $gs = new GSFeed();
        $globalFeed = $gs->client->feed($room, 'global');

        $options = ["recent" => true, 'id_gte' => $feedId, "counts" => true, "own" => true];
        $query = $globalFeed->getActivities(null, null, null, true, $options);

        $res = array_search($feedId, array_column($query['results'], 'id'));

        return response()->json($query['results'][$res], 200);
      }catch (\Exception $e){
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }

    public function updateFeedActivity (Request $request) {
        try {


          // Need a function to get the user id with token

            // $userId = $this->getUser($request['token']);
            $userId =$request['token'];
            if ($userId === $request['actor']['id']) {
                $gs = new GSFeed();
                $gs->client->updateActivities([$request->except(['props'])]);
                return response()->json(['success' => "The post has been updated"], 200);
            }else {
                return response()->json(['error' => "You don't have permissions"], 400);
            }
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function postActivity(Request $request) {
        try {
            $data = $request->all();
            // $data['id'] = $this->getUser($data['token']);
            $data['id'] = $request['userID'];
            $data['data']['actor']['id'] = $data['id'];

            $gs = new GSFeed();
            $feedUser = $gs->client->feed($data['room'], $data['id']);
            $res = $feedUser->addActivity($data['data']);

            return response()->json($feedUser->getActivities(), 200);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getGlobalActivities($room, $foreignKey) {
        try {
            $response = array();
            $gs = new GSFeed();
            $globalFeed = $gs->client->feed($room, $foreignKey);

            $options = ["recent" => true, "counts" => true, "own" => true];
            $query = $globalFeed->getActivities(null, null, null, true, $options);

            return response()->json($query['results'], 200);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
