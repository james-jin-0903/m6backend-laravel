<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\RationalizationFte;

class RationalizationFTEController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRationalizationFte(Request $request) {
        try {
            $this->validate($request, [
                'fte_costs' => 'numeric | max: 999999999999999999',
                'fte_count' => 'integer | digits_between: 1, 11',
                'fte_type' => 'integer',
                'app_id' => 'required | integer',
                'notes' => 'string'
            ]);
            $rationalizationFte = RationalizationFte::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'rationalization_fte_id' => $rationalizationFte->id
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
            $response = RationalizationFte::where('app_id',$appID)->with('type')->get();
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
    public function updateRationalizationFte(Request $request, $id) {
        try{
            $this->validate($request, [
                'fte_costs' => 'numeric | max: 999999999999999999',
                'fte_count' => 'integer | digits_between: 1, 11',
                'fte_type' => 'integer',
                'notes' => 'string'
            ]);
            $rationalizationFte = RationalizationFte::findOrFail($id);
            $rationalizationFte->update($request->except(['app_id']));
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
            $rationalizationFte = RationalizationFte::findOrFail($id);
            $rationalizationFte->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
