<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\RationalizationGovernance;

class RationalizationGovernanceController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRationalizationGovernance(Request $request) {
        try {
            $this->validate($request, [
                'estimated_days_to_replace' => 'string | max: 255',
                'primary_customer_group' => 'string | max: 255',
                'responsible_committee' => 'integer',
                'responsible_division' => 'integer',
                'responsible_manager' => 'string | max: 255',
                'first_contact_group' => 'integer',
                'responsible_vp_dir' => 'string | max: 255',
                'app_id' => 'required | integer'
            ]);
            $rationalizationGovernance = RationalizationGovernance::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'rationalization_governance_id' => $rationalizationGovernance->id
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
            $response = RationalizationGovernance::where('app_id',$appID)->with([
                'firstContactGroup', 'responsibleCommittee', 'responsibleDivision'
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
    public function updateRationalizationGovernance(Request $request, $id) {
        try{
            $this->validate($request, [
                'estimated_days_to_replace' => 'string | max: 255',
                'primary_customer_group' => 'string | max: 255',
                'responsible_committee' => 'integer',
                'responsible_division' => 'integer',
                'responsible_manager' => 'string | max: 255',
                'first_contact_group' => 'integer',
                'responsible_vp_dir' => 'string | max: 255'
            ]);
            $rationalizationGovernance = RationalizationGovernance::findOrFail($id);
            $rationalizationGovernance->update($request->except(['app_id']));
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
            $rationalizationGovernance = RationalizationGovernance::findOrFail($id);
            $rationalizationGovernance->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
