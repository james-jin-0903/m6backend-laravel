<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\RationalizationLicensing;

class RationalizationLicensingController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRationalizationLicensing(Request $request) {
        try {
            $this->validate($request, [
                'number_of_licenses' => 'integer | digits_between: 1, 11',
                'cost_per_license' => 'numeric | max: 999999999999999999',
                'purchase_type' => 'integer',
                'license_type' => 'integer',
                'total_cost' => 'numeric | max: 999999999999999999',
                'notes' => 'string',
                'app_id' => 'required | integer'
            ]);
            $rationalizationLicensing = RationalizationLicensing::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'rationalization_licensing_id' => $rationalizationLicensing->id
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
            $response = RationalizationLicensing::where('app_id',$appID)->with([
                'licenseType', 'purchaseType'
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
    public function updateRationalizationLicensing(Request $request, $id) {
        try{
            $this->validate($request, [
                'number_of_licenses' => 'integer | digits_between: 1, 11',
                'cost_per_license' => 'numeric | max: 999999999999999999',
                'purchase_type' => 'integer',
                'license_type' => 'integer',
                'total_cost' => 'numeric | max: 999999999999999999',
                'notes' => 'string'
            ]);
            $rationalizationLicensing = RationalizationLicensing::findOrFail($id);
            $rationalizationLicensing->update($request->except(['app_id']));
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
            $rationalizationLicensing = RationalizationLicensing::findOrFail($id);
            $rationalizationLicensing->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
