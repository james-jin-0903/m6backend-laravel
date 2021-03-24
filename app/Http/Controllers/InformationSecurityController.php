<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\InformationSecurity;

class InformationSecurityController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInformationSecurity(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                'ssn' => 'required | integer',
                'facing' => 'required | boolean',
                'phi' => 'required | boolean',
                'pci' => 'required | boolean'
            ]);

            $informationSecurity = InformationSecurity::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'information_security_id' => $informationSecurity->id
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
            return InformationSecurity::where('app_id',$appID)->first();
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
    public function updateInformationSecurity(Request $request, $id) {
        try{
            $this->validate($request, [
                'ssn' => 'integer',
                'facing' => 'boolean',
                'phi' => 'boolean',
                'pci' => 'boolean'
            ]);
            $infoSecurity = InformationSecurity::findOrFail($id);
            $infoSecurity->update($request->except(['app_id']));
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
            $infoSecurity = InformationSecurity::findOrFail($id);
            $infoSecurity->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
