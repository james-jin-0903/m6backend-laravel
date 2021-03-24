<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\InstallationGenerals;
use App\InstallationSupport as Support;
use App\InstallationAditionalInformation as AditionalInfo;

class InstallationGeneralController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInstallGeneral(Request $request) {
        try {
            $this->validate($request, [
                'priority' => 'integer | digits_between: 1, 11',
                'path_to_executable' => 'string | max: 255',
                'odbc_contact_name' => 'string | max: 255',
                'odbc_connection_required' => 'boolean',
                'odbc_settings' => 'string | max: 255',
                'general_notes' => 'string | max: 255',
                'ldap_ad_authentication' => 'integer',
                'windows_passed_dct' => 'integer',
                'app_id' => 'required | integer',
                'delivery_method' => 'integer',
                'install_type' => 'integer'
            ]);
            $installGeneral = InstallationGenerals::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'install_general' => $installGeneral->id
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
            $response = InstallationGenerals::where('app_id',$appID)->with([
                'ldapAdAuthentication', 'windowsPassedDct', 'deliveryMethod', 'installType'
            ])->get();
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
    public function updateInstallationGenerals(Request $request, $id) {
        try{
            $this->validate($request, [
                'priority' => 'integer | digits_between: 1, 11',
                'path_to_executable' => 'string | max: 255',
                'odbc_contact_name' => 'string | max: 255',
                'odbc_connection_required' => 'boolean',
                'odbc_settings' => 'string | max: 255',
                'general_notes' => 'string | max: 255',
                'ldap_ad_authentication' => 'integer',
                'windows_passed_dct' => 'integer',
                'delivery_method' => 'integer',
                'install_type' => 'integer'
            ]);
            $installGeneral = InstallationGenerals::findOrFail($id);
            $installGeneral->update($request->except(['app_id']));
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The record has been updated' ], 200);
    }

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateInstallation(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                // General Info
                'general_info.priority' => 'integer | digits_between: 1, 11 | nullable',
                'general_info.path_to_executable' => 'string | max: 255 | nullable',
                'general_info.odbc_contact_name' => 'string | max: 255 | nullable',
                'general_info.odbc_connection_required' => 'boolean | nullable',
                'general_info.odbc_settings' => 'string | max: 255 | nullable',
                'general_info.general_notes' => 'string | max: 255 | nullable',
                'general_info.ldap_ad_authentication' => 'integer | nullable',
                'general_info.windows_passed_dct' => 'integer | nullable',
                'general_info.delivery_method' => 'integer | nullable',
                'general_info.install_type' => 'integer | nullable',
                // Installation Support
                'installation_support.shortcut_modifications_note' => 'string | max: 255',
                'installation_support.firewall_exceptions_note' => 'string | max: 255',
                'installation_support.antivirus_exclusion_note' => 'string | max: 255',
                'installation_support.registry_changes_note' => 'string | max: 255',
                'installation_support.mapped_drives_note' => 'string | max: 255',
                'installation_support.install_notes_note' => 'string | max: 255',
                'installation_support.ini_changes_note' => 'string | max: 255',
                'installation_support.shortcut_modifications' => 'boolean',
                'installation_support.antivirus_exclusion' => 'boolean',
                'installation_support.firewall_exceptions' => 'boolean',
                'installation_support.registry_changes' => 'boolean',
                'installation_support.mapped_drives' => 'boolean',
                'installation_support.install_notes' => 'boolean',
                'installation_support.ini_changes' => 'boolean',
                // Aditional Build Info
                'aditional_build_info.previous_software_version' => 'string | max: 255',
                'aditional_build_info.groups_machine' => 'string | max: 255',
                'aditional_build_info.groups_user' => 'string | max: 255'
            ]);
            $req = $request->all();
            $req['aditional_build_info']['app_id'] = $request->app_id;
            $req['installation_support']['app_id'] = $request->app_id;
            $req['general_info']['app_id'] = $request->app_id;

            Support::updateOrCreate([
                'app_id' => $request->app_id
            ],$req['installation_support']);

            AditionalInfo::updateOrCreate([
                'app_id' => $request->app_id
            ],$req['aditional_build_info']);

            InstallationGenerals::updateOrCreate([
                'app_id' => $request->app_id
            ],$req['general_info']);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'Successful' ], 201);
    }


    /**
     * Remove the specified record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $installGeneral = InstallationGenerals::findOrFail($id);
            $installGeneral->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
