<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\InstallationAditionalInformation;

class InstallationAditionalInformationController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAditionalInformation(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                'previous_software_version' => 'string | max: 255',
                'groups_machine' => 'string | max: 255',
                'groups_user' => 'string | max: 255'
            ]);
            $installAditionalInfo = InstallationAditionalInformation::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'install_aditiona_iInfo_id' => $installAditionalInfo->id
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
            $response = InstallationAditionalInformation::where('app_id',$appID)->first();
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
    public function updateInstallationAditionalInformation(Request $request, $id) {
        try{
            $this->validate($request, [
                'previous_software_version' => 'string | max: 255',
                'groups_machine' => 'string | max: 255',
                'groups_user' => 'string | max: 255'
            ]);
            $installAditionalInfo = InstallationAditionalInformation::findOrFail($id);
            $installAditionalInfo->update($request->except(['app_id']));
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
            $installAditionalInfo = InstallationAditionalInformation::findOrFail($id);
            $installAditionalInfo->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
