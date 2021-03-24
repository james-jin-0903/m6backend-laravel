<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Configuration;

class ConfigurationController extends Controller {
    /**
     * Display a listing of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try{
            $Configuration = Configuration::all();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($Configuration, 200);
    }

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeConfig(Request $request) {
        try {
            $this->validate($request, [
                'potential_latency_sensitivity' => 'boolean',
                'verified_dependencies' => 'boolean',
                'web_browser_enabled' => 'boolean',
                'app_admin_rights' => 'boolean',
                'windows_platform' => 'boolean',
                'citrix_supported' => 'boolean',
                'dct_application' => 'boolean',
                'scw_application' => 'boolean',
                'other_platform' => 'boolean',
                'vlan_required' => 'boolean0',
                'client_server' => 'boolean',
                'static_ip' => 'boolean',
                'personal' => 'boolean',
                'ccow' => 'boolean',
                'app_id' => 'required|integer'
            ]);

            $configuration = Configuration::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'configuration_id' => $configuration->id
        ], 201);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByID($id) {
        try {
            $response = Configuration::where('id',$id)->first();
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
    public function updateConfig(Request $request, $id) {
        $this->validate($request, [
            'potential_latency_sensitivity' => 'boolean',
            'verified_dependencies' => 'boolean',
            'web_browser_enabled' => 'boolean',
            'app_admin_rights' => 'boolean',
            'windows_platform' => 'boolean',
            'citrix_supported' => 'boolean',
            'dct_application' => 'boolean',
            'scw_application' => 'boolean',
            'other_platform' => 'boolean',
            'vlan_required' => 'boolean0',
            'client_server' => 'boolean',
            'static_ip' => 'boolean',
            'personal' => 'boolean',
            'ccow' => 'boolean'
        ]);
        try{
            $configuration = Configuration::findOrFail($id);
            $configuration->update($request->except(['app_id']));
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
            $configuration = Configuration::findOrFail($id);
            $configuration->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
