<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\SpecificationMaintenances;

class SpecificationMaintenanceController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSpecificationMaintenance(Request $request) {
        try {
            $this->validate($request, [
                'minimum_disc_space_required' => 'string | max: 255',
                'minimum_memory_required' => 'string | max: 255',
                'patching_responsibility' => 'string | max: 255',
                'future_os_service_pack' => 'string | max: 255',
                'typical_memory_usage' => 'string | max: 255',
                'network_connection' => 'string | max: 255',
                'switch_ip_address' => 'string | max: 255',
                'installation_date' => 'date',
                'network_zone_type' => 'integer',
                'operating_system' => 'integer',
                'future_op_system' => 'integer',
                'ip_address_type' => 'integer',
                'os_service_pack' => 'string | max: 255',
                'patching_method' => 'string | max: 255',
                'set_for_refresh' => 'date',
                'patching_notes' => 'string',
                'network_notes' => 'string',
                'refresh_date' => 'date',
                'installed_by' => 'string | max: 255',
                'mac_address' => 'string | max: 255',
                'last_reboot' => 'date',
                'last_login' => 'date',
                'ip_address' => 'string | max: 255',
                'recovery' => 'string | max: 255',
                'app_id' => 'required | integer'
            ]);
            $specificationMaintenance = SpecificationMaintenances::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'specification_maintenance_id' => $specificationMaintenance->id
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
            $response = SpecificationMaintenances::where('app_id',$appID)->with([
                'networkZoneType','ipAddressType','futureOpSystem','operatingSystem'
            ])->first();
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
    public function updateSpecificationMaintenance(Request $request, $id) {
        try{
            $this->validate($request, [
                'minimum_disc_space_required' => 'string | max: 255',
                'minimum_memory_required' => 'string | max: 255',
                'patching_responsibility' => 'string | max: 255',
                'future_os_service_pack' => 'string | max: 255',
                'typical_memory_usage' => 'string | max: 255',
                'network_connection' => 'string | max: 255',
                'switch_ip_address' => 'string | max: 255',
                'installation_date' => 'date',
                'network_zone_type' => 'integer',
                'operating_system' => 'integer',
                'future_op_system' => 'integer',
                'ip_address_type' => 'integer',
                'os_service_pack' => 'string | max: 255',
                'patching_method' => 'string | max: 255',
                'set_for_refresh' => 'date',
                'patching_notes' => 'string',
                'network_notes' => 'string',
                'refresh_date' => 'date',
                'installed_by' => 'string | max: 255',
                'mac_address' => 'string | max: 255',
                'last_reboot' => 'date',
                'last_login' => 'date',
                'ip_address' => 'string | max: 255',
                'recovery' => 'string | max: 255'
            ]);
            $specificationMaintenance = SpecificationMaintenances::findOrFail($id);
            $specificationMaintenance->update($request->except(['app_id']));
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
            $specificationMaintenance = SpecificationMaintenances::findOrFail($id);
            $specificationMaintenance->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
