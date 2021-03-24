<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\SpecificationCertifications;

class SpecificationCertificationController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSpecificationCertification(Request $request) {
        try {
            $this->validate($request, [
                'first_certificated' => 'date',
                'time_to_certified' => 'string | max: 255',
                'maint_certi_type' => 'integer',
                'expiration_date' => 'date',
                'start_date' => 'date',
                'compliant' => 'boolean',
                'certified' => 'boolean',
                'required' => 'boolean',
                'name' => 'string | max: 255',
                'app_id' => 'required | integer'
            ]);
            $specificationCertification = SpecificationCertifications::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'specification_certification_id' => $specificationCertification->id
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
            return SpecificationCertifications::where('app_id',$appID)->with('type')->get();
        } catch(QueryException $e) {
            return response( $e->getMessage(), 501);
        }
    }
    /**
     * Update the specified record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSpecificationCertification(Request $request, $id) {
        try{
            $this->validate($request, [
                'first_certificated' => 'date',
                'time_to_certified' => 'string | max: 255',
                'maint_certi_type' => 'integer',
                'expiration_date' => 'date',
                'start_date' => 'date',
                'compliant' => 'boolean',
                'certified' => 'boolean',
                'required' => 'boolean',
                'name' => 'string | max: 255'
            ]);
            $specificationCertification = SpecificationCertifications::findOrFail($id);
            $specificationCertification->update($request->except(['app_id']));
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
            $specificationCertification = SpecificationCertifications::findOrFail($id);
            $specificationCertification->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
