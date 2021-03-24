<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\GSFeedController as GSFeed;
use App\WorkActivityModel;
use App\WoAssignments;
use App\AppsSettings;

class WorkActivityController extends Controller {

    /**
     * Store a newly created work in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWork(Request $request) {
      try {
        $this->validate($request, [
          'appointment_time'=> 'string | nullable |max: 255',
          'meeting_time'    => 'string | nullable |max: 255',
          'father_post_id'  => 'string | nullable |max: 255',
          'location'        => 'string | nullable |max: 255',
          'colors'          => 'string | nullable |max: 60',
          'title'           => 'string | max: 255',
          'description'     => 'string | nullable',
          'author'          => 'string | max: 255',
          'company_id'      => 'integer | nullable',
          'application_id'  => 'integer | nullable',
          'record_id'       => 'integer | nullable',
          'type'            => 'integer | nullable',
          'requested_date'  => 'date',
          'start_date'      => 'date',
          'due_date'        => 'date',
          'end_date'        => 'date',
          'status'          => 'string | max: 255'
        ]);

        $itm = $request;

        if ($itm->default) {
          $itm->type = $this->validateDefault($itm->default);
        }
        $res = $this->postActivity($itm['activity'], $itm['company_id']);

        $itm['activity_number'] = $this->makeKey();
        $itm['post_id'] = $res->original['id'];
        $itm['status'] = 'Pending';


        if ($itm['application_id'] <= 0) {
          $itm['application_id'] = null;
        }

        $activity = WorkActivityModel::create($itm->except(['activity', 'assignment_list']));
        if ( gettype( $itm['assignment_list'] ) !== 'NULL' ) {
          $this->postAssignments( $itm['assignment_list'], $activity->id );
        }
      } catch(\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
      }
      return response()->json([
        'message' => 'Successful',
        'work_id' => $activity->id
      ], 201);
    }
    /**
     * Display the specified group of work or tag.
     *
     * @param  $column
     * @param  $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWorkByConsult($userId, $keyQuery, $companyID) {
        try{
          $gs = new GSFeed();
          $keys = Array();
          $response = Array();

          $globalFeed = $gs->client->feed('work_order', $companyID);
          $responseAssign = WoAssignments::where('assignee', $userId)->get()->groupBy('work_id');

          foreach ($responseAssign as $key => $val) {
              array_push($keys, $key);
          }

          $ativities = WorkActivityModel::whereIn('id', $keys)
            ->orWhere('author', $userId)
            ->with(['woAssignments', 'record', 'application', 'type', 'type.fields', 'type.fields.app_field'])
            ->get();

            if (count($ativities) > 0) {
              $options = ["recent" => true, "counts" => true, "own" => true];
              $posts = $globalFeed->getActivities(null, null, null, true, $options);

              foreach ($ativities as $key => $value) {
                $res = array_search($value['post_id'], array_column($posts['results'], 'id'));
                if ($res !== false) {
                  $ativities[$key]['post'] = $posts['results'][$res];
                  array_push($response, $ativities[$key]);
                }
              }
            }

            switch ($keyQuery) {
                case 'everyone':
                    return response()->json($response, 200);
                    break;
                case 'all_apps':
                    $filter = array();
                    foreach ($response as $value) {
                        if ($value->record) {
                            array_push($filter, $value);
                        }
                    }
                    return response()->json($filter, 200);
                    break;
                case 'itapps':
                    $filter = array();
                    foreach ($response as $value) {
                        if ($value->record['app_type'] === 'itapps') {
                            array_push($filter, $value);
                        }
                    }
                    return response()->json($filter, 200);
                    break;
                default:
                    return response()->json([], 200);
                    break;
            }

        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display the specified group of work or tag.
     *
     * @param  $column
     * @param  $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWorkByIds(Request $ids) {
        try{
          $ativities = WorkActivityModel::whereIn('id', $ids['data'])
            ->with(['woAssignments', 'record', 'application', 'type', 'type.fields', 'type.fields.app_field'])
            ->get();

          return response()->json($ativities, 200);

        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Update the specified work in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWork(Request $request, $id) {
        try{
            $this->validate($request, [
                'appointment_time'=> 'string | nullable |max: 255',
                'meeting_time'    => 'string | nullable |max: 255',
                'location'        => 'string | nullable |max: 255',
                'colors'          => 'string | nullable |max: 60',
                'status'          => 'string | max: 255',
                'title'           => 'string | max: 255',
                'description'     => 'string | nullable',
                'application_id'  => 'integer | nullable',
                'record_id'       => 'integer | nullable',
                'type'            => 'integer | nullable',
                'requested_date'  => 'date',
                'start_date'      => 'date',
                'due_date'        => 'date',
                'end_date'        => 'nullable | date'
            ]);
            $activity = WorkActivityModel::findOrFail($id);
            $activity->update($request->except(['id', 'author', 'post_id', 'company_id']));

            if ($request['assignment_list'] !== $request['preview_list']) {
              $this->deleteAssign($request['assignment_list'], $id);
              response()->json($this->updateAssignment($request['assignment_list'], $id), 200);
            }
            return response()->json($activity);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The work has been updated' ], 200);
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $activity = WorkActivityModel::findOrFail($id);
            $activity->delete();

            return response()->json(['message' => "The work has been deleted" ], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => "The work was not found" ], 404);
        }
    }

    // PROTECTED
    /**
     * Check if the type exist.
     *
     * @return \Illuminate\Http\Response
     */
    protected function validateDefault($taskId) {
      $res = AppsSettings::where([['value', $taskId], ['field', 'wo_request_type']])->first();
      if(!$res) {
        $res = AppsSettings::where([['value', 'General'], ['field', 'wo_request_type']])->first();

        $newSetting = new AppsSettings;
        $newSetting->field = 'wo_request_type';
        $newSetting->value = $taskId;
        $newSetting->parent_id = $res->id;
        $newSetting->app_type = 'm6works';
        $newSetting->save();

        return $newSetting->id;
      }
      return $res->id;
    }

    /**
     * Remove the specified tag from storage.
     *
     * @return \Illuminate\Http\Response
     */
    protected function makeKey() {
        $appCount = WorkActivityModel::all()->count();
        $appNumber = str_pad($appCount+1, 8, '0', STR_PAD_LEFT);

        return 'Act-'.$appNumber.'-'.date('Y');
    }


    protected function postActivity($activity, $id) {
      try {
        $gs = new GSFeed();
        $feedUser = $gs->client->feed('work_order', $id);
        $res = $feedUser->addActivity($activity['data']);

        return response()->json($res, 200);
      }catch (\Exception $e){
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }

    protected function postAssignments($users, $workID) {
        try {
            foreach ($users as $key=>$userID) {
                $users[$key] = Array(
                    "work_id"   => $workID,
                    "assignee"  => $userID,
                    "status"    => 'Pending'
                );
            }

            WoAssignments::insert($users);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $users;
    }

    protected function updateAssignment($assign, $workID) {
        try{
            foreach ($assign as $key => $val) {
                if(!isset($val['id'])){
                    $assign[$key] = Array(
                        "id"        => null,
                        "work_id"   => $workID,
                        "assignee"  => $val,
                        "status"    => 'Pending'
                    );
                }
                $response = WoAssignments::updateOrCreate([
                    'work_id'  =>  $workID,
                    'assignee' =>  $val
                ],$assign[$key]);
            }
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function deleteAssign($current, $activityID) {

      $before = WoAssignments::where('work_id', $activityID)->get();

      if(count($current) > 0) {
        foreach ($current as $cur) {
          foreach($before as $key => $item) {
            if ($cur === $item->assignee) {
              unset($before[$key]);
              break;
            }
          }
        }
      }

      foreach($before as $activity) {
          $activity->delete();
      }
      return $before;
    }

  /**
   * Get all Actions
   *
   * @return Response
   */
  public function getAllActions() {
    try {
      $actions = WorkActivityModel::with(['woAssignments'])->get();
      return response()->json($actions);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
