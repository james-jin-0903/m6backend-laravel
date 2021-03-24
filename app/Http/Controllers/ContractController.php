<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Contracts;

class ContractController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeContract(Request $request) {
        try {
            $this->validate($request, [
                'term_notice_period' => 'required | integer | digits_between: 1, 11',
                'capped_inflator_value' => 'required | string | max: 255',
                'capped_inflator' => 'required | string | max: 255',
                'term_length' => 'integer | digits_between: 1, 11',
                'contract_name' => 'required | string | max: 255',
                'term_until' => 'required | integer',
                'critical_decision_date' => 'date',
                'app_id' => 'required | integer',
                'status' => 'required | boolean',
                'number' => 'string | max: 255',
                'finish_contract' => 'date',
                'start_contract' => 'date'
            ]);

            $contracts = Contracts::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'contract_id' => $contracts->id
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
            $response = Contracts::where('app_id',$appID)->get();
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
    public function updateContract(Request $request, $id) {
        $this->validate($request, [
            'term_length' => 'integer | digits_between: 1, 11',
            'capped_inflator_value' => 'string | max: 255',
            'capped_inflator' => 'string | max: 255',
            'contract_name' => 'string | max: 255',
            'critical_decision_date' => 'date',
            'term_notice_period' => 'integer',
            'number' => 'string | max: 255',
            'finish_contract' => 'date',
            'start_contract' => 'date',
            'term_until' => 'integer',
            'app_id' => 'integer',
            'status' => 'boolean'
        ]);
        try{
            $contract = Contracts::findOrFail($id);
            $contract->update($request->except(['app_id']));
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
            $contract = Contracts::findOrFail($id);
            $contract->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
