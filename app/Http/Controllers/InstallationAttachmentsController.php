<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\InstallationAttachments;

class InstallationAttachmentsController extends Controller {

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeIstallAttachment(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                'attachment' => 'string | max: 255',
                'file_name' => 'string | max: 255',
                'file_url' => 'string | max: 255',
                'app_id' => 'string | max: 255',
                'revision_notes' => 'string'
            ]);
            $installAdttachments = InstallationAttachments::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'install_attachments' => $installAdttachments->id
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
            $response = InstallationAttachments::where('app_id',$appID)->get();
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
    public function updateInstallationAttachments(Request $request, $id) {
        try{
            $this->validate($request, [
                'attachment' => 'string | max: 255',
                'file_name' => 'string | max: 255',
                'file_url' => 'string | max: 255',
                'app_id' => 'string | max: 255',
                'revision_notes' => 'string'
            ]);
            $installAdttachments = InstallationAttachments::findOrFail($id);
            $installAdttachments->update($request->except(['app_id']));
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
            $installAdttachments = InstallationAttachments::findOrFail($id);
            $installAdttachments->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
