<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\GenericFeed;
use App\GSFeedUser;

class GSGroupController extends Controller {

  protected function getUser($token) {
    $userId = app()->make('CognitoSingleton')->getUser([
      'AccessToken' => $token
    ]);
    return $userId->get('Username');
  }

  /**
   * Display the specified groups .
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getFeedRoomsByUserToken(Request $request) {
    try {
      $userID = $this->getUser($request->token);

      $response = GSFeedUser::where('user_id',$userID)->select('feed_id')->with('FeedGroup.users')->get();
    }catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($response, 200);
  }

  /**
   * Store a newly created feed group and partisipants in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeFeedGroup(Request $request) {
    try {
      $this->validate($request, [
        'token'       => 'required | string',
        'name'        => 'string | max:255',
        'description' => 'string',
        "users"       => "required | array",
        "users.*"     => "required | string | distinct"
      ]);
      $user = $this->getUser($request->token);
      $data = $request->all();

      $data['owner_id'] = $user;
      $position = array_search($user, (array) $request->users);

      if (gettype ( $position ) === 'boolean') {
        array_push ( $data['users'], $user );
      }

      $data['key'] = \Illuminate\Support\Str::random(5).'.'.microtime(TRUE).'.'.\Illuminate\Support\Str::random(5);
      $data['key'] = str_replace(".", "-", $data['key']);
      $feedGroup = GenericFeed::create($data);

      $this->storeNewlyFeedUsers($data['users'], $user, $feedGroup->id);
    } catch(\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json([
        'message' => 'Successful',
        'feed_group_id' => $feedGroup->id
    ], 201);
  }

  /**
   * Update an existent feed group in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function updateFeedRoom($id, Request $request) {
    try {
      $this->validate($request, [
        'token'        => 'required | string',
        'name'         => 'string | max:255',
        'description'  => 'string'
      ]);
      $user = $this->getUser($request->token);

      $feedRoom = GenericFeed::findOrFail($id);
      if ($user === $feedRoom['owner_id']) {
        $feedRoom->update($request->except(['owner_id']));
        return response()->json(['success' => "The room has been updated"], 400);
      } else {
        return response()->json(['error' => "You don't have permissions"], 400);
      }

    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Remove the specified record from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroyFeedRoom( Request $request ) {
    try{
      $this->validate($request, [
        'id'    => 'required | integer',
        'token' => 'required | string'
      ]);

      $user = $this->getUser($request->token);

      $feedGroup = GenericFeed::findOrFail($request->id);

      if ($feedGroup['owner_id'] === $user) {
        $feedGroup->delete();
        return response()->json(['message' => "The group has been deleted" ], 200);
      } else {
        return response()->json(['error' => "You don't have permissions"], 400);
      }
    } catch(\Exception $e) {
        return response()->json(['error' => "The group was not found" ], 404);
    }
  }

  public function updateUsersGroup(Request $request) {
    try {
      $this->validate($request, [
        "users"           => "array",
        'group_id'        => 'required | integer',
        'token'           => 'required | string',
        "users.*.user_id" => "required | string | max:255",
        "users.*.role"    => "required | string | max:255"
      ]);
      $user = $this->getUser($request->token);

      $data = $request;

      $response = GSFeedUser::where('feed_id',$request->group_id)->get();

      $filtered = $response->filter(function ($value, $key) use ($user){
        return $value['user_id'] == $user;
      });

      if ( count($filtered) === 0 || $filtered->first()['role'] !== 'owner' &&  $filtered->first()['role'] !== 'admin' ) {
        return response()->json(['error' => "You don't have permissions"], 400);
      }


      $this->deleteFeedUsers($response, $request->users, $request->group_id);
      $this->updateFeedUsers($request->users, $request->group_id);

      return response()->json(['success' => 'The users were successfully updated'], 200);

    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Delete a partisipants in feed group storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  protected function deleteFeedUsers($beforeUsers, $currentUsers, $groupId) {
    foreach ($currentUsers as $cur) {
      $pos = -1;
      $filtered = $beforeUsers->filter(function ($value, $key) use ($cur){
        return $value['user_id'] == $cur['user_id'];
      });

      if (count($filtered) > 0){
        $pos = array_search($filtered->first(), (array) $beforeUsers);
      }

      if($pos>=0){
        unset($beforeUsers[$pos]);
      }
    }
    foreach($beforeUsers as $bef) {
      if ($bef['role'] !== 'owner') {
        GSFeedUser::where('id', $bef['id'])->delete();
      }
    }
  }

  protected function updateFeedUsers($users, $groupId) {
    foreach ($users as $key => $user) {
      $user['feed_id'] = $groupId;

      GSFeedUser::updateOrCreate(
        [ 'user_id' => $user['user_id'], 'feed_id' => $groupId ],
        $user
      );
    }
  }

  /**
   * Store a newly partisipants in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  protected function storeNewlyFeedUsers($users, $owner, $group) {
    try {
      foreach ($users as $user) {
        $userFeed  = new GSFeedUser;
        $userFeed->user_id = $user;
        $userFeed->role    = $owner === $user ? 'owner' : 'member';
        $userFeed->feed_id = $group;
        $userFeed->save();
      }
    } catch(\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
