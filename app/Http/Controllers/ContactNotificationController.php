<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\ContactNotification;

class ContactNotificationController extends Controller {
    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeContactNotification(Request $request) {
        try {
            $this->validate($request, [
                'notification_id' => 'required|integer',
                'contact_id' => 'required|string|max:255'
            ]);

            $contactNotification = ContactNotification::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'contact_notification_id' => $contactNotification->id
        ], 201);
    }

    /**
     * Display the specified group of records or record.
     *
     * @param  $column
     * @param  $value
     * @return \Illuminate\Http\Response
     */
    public function showContactNotificationByConsult($column, $value) {
        try{
            $response = ContactNotification::where($column,$value)->with('notification')->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByID($id) {
        try {
            $response = ContactNotification::where('id',$id)->with('notification')->first();
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
    public function updateContactNotification(Request $request, $id) {
        try{
            $this->validate($request, [
                'notification_id' => 'integer',
                'contact_id'  => 'string | max:255'
            ]);

            $contactNotification = ContactNotification::findOrFail($id);
            $contactNotification->update($request->except(['app_id']));
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
            $tag = ContactNotification::findOrFail($id);
            $tag->delete();

            return response()->json([ 'message' => 'The record has been deleted' ], 200);
        } catch(QueryException $e) {
            return response()->json([ 'error' => 'The record was not found' ], 404);
        }
    }
}
