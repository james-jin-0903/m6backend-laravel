<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\RationalizationCosts;

class RationalizationCostController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRationalizationCost(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                'cost_category' => 'integer',
                'cost_owner' => 'integer',
                'cost_type' => 'integer',
                'period' => 'integer',
                'notes' => 'string',
                'cost' => 'numeric | max: 999999999999999999'
            ]);
            $rationalizationCosts = RationalizationCosts::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'rationalization_costs_id' => $rationalizationCosts->id
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
            $rationalizationCosts = RationalizationCosts::where('app_id',$appID)->with([
                'getPeriod', 'owner', 'type', 'category'
            ])->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response($rationalizationCosts, 200);
    }
    /**
     * Update the specified record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRationalizationCost(Request $request, $id) {
        try{
            $this->validate($request, [
                'cost_category' => 'integer',
                'cost_owner' => 'integer',
                'cost_type' => 'integer',
                'period' => 'integer',
                'notes' => 'string',
                'cost' => 'numeric | max: 999999999999999999'
            ]);
            $rationalizationCosts = RationalizationCosts::findOrFail($id);
            $rationalizationCosts->update($request->except(['app_id']));
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
            $rationalizationCosts = RationalizationCosts::findOrFail($id);
            $rationalizationCosts->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
