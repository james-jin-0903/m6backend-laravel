<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\AppInfoGeneral;

class AppInfoGeneralController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAppInfoGeneral(Request $request) {
        try {
            $this->validate($request, [
                'first_contact_group_settings_id' => 'required|integer',
                'server_hosting_model_settings_id'  => 'integer',
                'status_settings_id' => 'required|integer',
                'app_management_settings_id' => 'integer',
                'vendor_id' => 'required|string|max:255',
                'sub_category_settings_id' => 'integer',
                'version' => 'required|string|max:255',
                'category_settings_id' => 'integer',
                'type_settings_id' => 'integer',
                'app_id' => 'required|integer',
                'capabilities' => 'integer'
            ]);

            $allValues = $request->all();
            $appInfoGeneral = AppInfoGeneral::create($allValues);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'app_information_general_id' => $appInfoGeneral->id
        ], 201);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByAppID($appID) {
        try {
            $appGeneralInfo = AppInfoGeneral::where('app_id',$appID)->with(
                ['status', 'firstContactGroup', 'category','subCategory', 'type', 'appManagement',
                'serverHostingModel', 'capability']
            )->first();
        } catch(QueryException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'appId' => $itapp->id
            ], 501);
        }
        return response()->json($appGeneralInfo, 200);
    }
    /**
     * Update the specified record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAppInfoGeneral(Request $request, $id) {
        try{
            $this->validate($request, [
                'server_hosting_model_settings_id'  => 'integer',
                'first_contact_group_settings_id' => 'integer',
                'app_management_settings_id' => 'integer',
                'sub_category_settings_id' => 'integer',
                'category_settings_id' => 'integer',
                'status_settings_id' => 'integer',
                'type_settings_id' => 'integer',
                'capabilities' => 'integer',
                'vendor_id' => 'string|max:255',
                'version' => 'string|max:255'
            ]);

            $appInfoGeneral = AppInfoGeneral::findOrFail($id);
            $appInfoGeneral->update($request->except(['app_id']));
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
            $appInfoGeneral = AppInfoGeneral::findOrFail($id);
            $appInfoGeneral->delete();

            return response()->json([ 'message' => 'The record has been deleted' ], 200);
        } catch(QueryException $e) {
            return response()->json([ 'error' => 'The record was not found' ], 404);
        }
    }
}
