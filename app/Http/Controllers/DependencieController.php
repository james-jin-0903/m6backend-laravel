<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Dependencies;

class DependencieController extends Controller {
    /**
     * Display a listing of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try{
            $dependencies = Dependencies::where('deleted_at', null)->with(
                ['appCompliant', 'dctStatus', 'updateInstallNotes', 'eda', 'appBuild', 'type']
            )->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($dependencies, 200);
    }

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDependencie(Request $request) {
        try {
            $this->validate($request, [
                'dependency_update_install_notes' => 'integer',
                'dependency_update_exec_path' => 'integer',
                'dependency_type' => 'required | integer',
                'dependency_app_compliant' => 'integer',
                'dependency_dct_status' => 'integer',
                'dependency_app_build' => 'integer',
                'app_id' => 'required | integer',
                'dependency_eda' => 'integer',
                'version' => 'required | string | max: 255',
                'remediation_date' => 'required | date',
                'status' => 'required | boolean',
                'notes' => 'string'
            ]);

            $dependencie = Dependencies::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'dependency_id' => $dependencie->id
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
            $response = Dependencies::where('app_id',$appID)->with(
                ['appCompliant', 'dctStatus', 'updateInstallNotes', 'eda', 'appBuild', 'type', 'updateExecPath']
            )->get();
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
    public function updateDependencies(Request $request, $id) {
        try{
            $this->validate($request, [
                'dependency_update_install_notes' => 'integer',
                'dependency_update_exec_path' => 'integer',
                'dependency_app_compliant' => 'integer',
                'dependency_dct_status' => 'integer',
                'dependency_app_build' => 'integer',
                'version' => 'string | max: 255',
                'dependency_type' => 'integer',
                'dependency_eda' => 'integer',
                'remediation_date' => 'date',
                'status' => 'boolean',
                'notes' => 'string'
            ]);
            $dependencie = Dependencies::findOrFail($id);
            $dependencie->update($request->except(['app_id']));
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
            $dependencie = Dependencies::findOrFail($id);
            $dependencie->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
