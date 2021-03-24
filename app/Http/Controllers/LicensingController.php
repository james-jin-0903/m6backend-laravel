<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Licensing;

class LicensingController extends Controller {
    /**
     * Display a listing of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try{
            $licensing = Licensing::where('deleted_at',null)->with('type')->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($licensing, 200);
    }

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLicensing(Request $request) {
        try {
            $this->validate($request, [
                'estimated_current_users' => 'integer | digits_between: 1, 11',
                'number_of_licenses' => 'integer | digits_between: 1, 11',
                'estimated_users' => 'integer | digits_between: 1, 11',
                'details' => 'string | max: 255',
                'app_id' => 'required | integer',
                'licensing_type' => 'integer'
            ]);
            $licensing = Licensing::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'licensing_id' => $licensing->id
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
            $response = Licensing::where('app_id',$appID)->with('type')->first();
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
    public function updateLicensing(Request $request, $id) {
        try{
            $this->validate($request, [
                'estimated_current_users' => 'integer | digits_between: 1, 11',
                'number_of_licenses' => 'integer | digits_between: 1, 11',
                'estimated_users' => 'integer | digits_between: 1, 11',
                'details' => 'string | max: 255',
                'licensing_type' => 'integer'
            ]);
            $licensing = Licensing::findOrFail($id);
            $licensing->update($request->except(['app_id']));
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
            $licensing = Licensing::findOrFail($id);
            $licensing->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
