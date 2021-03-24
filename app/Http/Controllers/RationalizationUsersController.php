<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\RationalizationUsers;

class RationalizationUsersController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRationalizationUsers(Request $request) {
        try {
            $this->validate($request, [
                'user_type' => 'integer',
                'users' => 'integer | digits_between: 1, 11',
                'notes' => 'string',
                'app_id' => 'required | integer'
            ]);
            $rationalizationUser = RationalizationUsers::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'rationalization_user_id' => $rationalizationUser->id
        ], 201);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByAppID($appID) {
        try {
            $response = RationalizationUsers::where('app_id',$appID)->with('type')->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }
    /**
     * Update the specified record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRationalizationUsers(Request $request, $id) {
        try{
            $this->validate($request, [
                'user_type' => 'integer',
                'users' => 'integer | digits_between: 1, 11',
                'notes' => 'string'
            ]);
            $rationalizationUser = RationalizationUsers::findOrFail($id);
            $rationalizationUser->update($request->except(['app_id']));
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The record has been updated' ], 200);
    }

    /**
     * Remove the specified record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $rationalizationUser = RationalizationUsers::findOrFail($id);
            $rationalizationUser->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
