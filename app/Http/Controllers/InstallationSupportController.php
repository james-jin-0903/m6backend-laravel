<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\InstallationSupport;

class InstallationSupportController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInstallSupport(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                'shortcut_modifications_note' => 'string | max: 255',
                'firewall_exceptions_note' => 'string | max: 255',
                'antivirus_exclusion_note' => 'string | max: 255',
                'registry_changes_note' => 'string | max: 255',
                'mapped_drives_note' => 'string | max: 255',
                'install_notes_note' => 'strin | max: 255',
                'ini_changes_note' => 'string | max: 255',
                'shortcut_modifications' => 'boolean',
                'antivirus_exclusion' => 'boolean',
                'firewall_exceptions' => 'boolean',
                'registry_changes' => 'boolean',
                'mapped_drives' => 'boolean',
                'install_notes' => 'boolean',
                'ini_changes' => 'boolean'
            ]);
            $installAditionalInfo = InstallationSupport::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'install_aditional_info_id' => $installAditionalInfo->id
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
            $response = InstallationSupport::where('app_id',$appID)->first();
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
    public function updateInstallationSupport(Request $request, $id) {
        try{
            $this->validate($request, [
                'shortcut_modifications_note' => 'string | max: 255',
                'firewall_exceptions_note' => 'string | max: 255',
                'antivirus_exclusion_note' => 'string | max: 255',
                'registry_changes_note' => 'string | max: 255',
                'mapped_drives_note' => 'string | max: 255',
                'install_notes_note' => 'strin | max: 255',
                'ini_changes_note' => 'string | max: 255',
                'shortcut_modifications' => 'boolean',
                'antivirus_exclusion' => 'boolean',
                'firewall_exceptions' => 'boolean',
                'registry_changes' => 'boolean',
                'mapped_drives' => 'boolean',
                'install_notes' => 'boolean',
                'ini_changes' => 'boolean'
            ]);
            $installAditionalInfo = InstallationSupport::findOrFail($id);
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
            $installAditionalInfo = InstallationSupport::findOrFail($id);
            $installAditionalInfo->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
