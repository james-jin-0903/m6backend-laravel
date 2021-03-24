<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WoAssignments;
use App\WorkActivityModel;

class WoAssignmentsController extends Controller {

    /**
     * Store a newly created Assignment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAssignment(Request $request) {
        try {
            $this->validate($request, [
                'work_id'   => 'integer | nullable',
                'assignee'  => 'string | max: 255',
            ]);
            $activity = $request;
            $activity['status'] = 'Pending';

            $newActivity = WoAssignments::create($activity->all());
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'Assignment_id' => $newActivity->id
        ], 201);
    }
    /**
     * Display the specified group of Assignment or tag.
     *
     * @param  $column
     * @param  $value
     * @return \Illuminate\Http\Response
     */
    public function showAssignmentByConsult($column, $value) {
        try{
            $response = WoAssignments::where($column,$value)->get();
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }
    /**
     * Update the specified Assignment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAssignment(Request $request, $id) {
        try{
            $this->validate($request, [
                'assignee'  => 'string | max: 255',
                'status'    => 'string'
            ]);
            $tag = WoAssignments::findOrFail($id);
            $tag->update($request->all());
            if ($request->status) {
                $activity = WorkActivityModel::findOrFail($tag->work_id);
                $this->updateActivityState($activity);
            }
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The Assignment has been updated' ], 200);
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $tag = WoAssignments::findOrFail($id);
            $tag->delete();

            return response()->json(['message' => "The Assignment has been deleted" ], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => "The Assignment was not found" ], 404);
        }
    }


    protected function updateActivityState($activity){
        try{
            $assign = WoAssignments::where('work_id', $activity->id)->get();
            $declined = count($assign->where('status', 'Declined'));

            if ($declined > 0) {
                # Is declined
                $activity->update(['status' => 'Declined']);
            }else {
                # On progress or Complete
                $complete = count($assign->where('status', 'Complete')) === count($assign);
                $complete === true ? $activity->update(['status' => 'Complete']) : $activity->update(['status' => 'In Progress']);
            }
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
