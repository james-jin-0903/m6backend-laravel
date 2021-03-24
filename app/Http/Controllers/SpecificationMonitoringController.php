<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\SpecificationMonitoring;

class SpecificationMonitoringController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSpecificationMonitoring(Request $request) {
        try {
            $this->validate($request, [
                'connection_protocol_used' => 'string | max: 255',
                'dashboard_available' => 'boolean',
                'how_monitored' => 'string | max: 255',
                'system_used' => 'string | max: 255',
                'app_id' => 'required | integer',
                'notes' => 'string'
            ]);
            $specificationMonitoring = SpecificationMonitoring::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'specification_monitoring_id' => $specificationMonitoring->id
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
            $response = SpecificationMonitoring::where('app_id',$appID)->first();
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
    public function updateSpecificationMonitoring(Request $request, $id) {
        try{
            $this->validate($request, [
                'connection_protocol_used' => 'string | max: 255',
                'dashboard_available' => 'boolean',
                'how_monitored' => 'string | max: 255',
                'system_used' => 'string | max: 255',
                'notes' => 'string'
            ]);
            $SpecificationMonitoring = SpecificationMonitoring::findOrFail($id);
            $SpecificationMonitoring->update($request->except(['app_id']));
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
            $SpecificationMonitoring = SpecificationMonitoring::findOrFail($id);
            $SpecificationMonitoring->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
