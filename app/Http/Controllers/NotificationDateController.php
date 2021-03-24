<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\NotificationDate;

class NotificationDateController extends Controller {
    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNotificationDate(Request $request) {
        try {
            $this->validate($request, [
                'date' => 'required | date',
                'recurrence' => 'string | max: 255',
                'daily_every_x_day' => 'integer | digits_between: 1, 11',
                'daily_every_weekday' => 'integer | digits_between: 1, 11',
                'weekly_recur_every_x_week' => 'integer | digits_between: 1, 11',
                'monthly_day' => 'integer | digits_between: 1, 11',
                'monthly_every_month' => 'integer | digits_between: 1, 11',
                'monthly_ordinal' => 'integer',
                'monthly_month' => 'integer',
                'yearly_recur_years' => 'integer | digits_between: 1, 11',
                'yearly_day' => 'integer | digits_between: 1, 11',
                'yearly_ordinal' => 'integer',
                'yearly_month' => 'integer',
                'yearly_week_day' => 'integer'
            ]);

            $notificationDate = NotificationDate::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'notification_date_id' => $notificationDate->id
        ], 201);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByID($id) {
        try {
            $response = NotificationDate::where('id',$id)->first();
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
    public function updateNotificationDate(Request $request, $id) {
        try{
            $this->validate($request, [
                'date' => 'date',
                'recurrence' => 'string | max: 255',
                'daily_every_x_day' => 'integer | digits_between: 1, 11',
                'daily_every_weekday' => 'integer | digits_between: 1, 11',
                'weekly_recur_every_x_week' => 'integer | digits_between: 1, 11',
                'monthly_day' => 'integer | digits_between: 1, 11',
                'monthly_every_month' => 'integer | digits_between: 1, 11',
                'monthly_ordinal' => 'integer',
                'monthly_month' => 'integer',
                'yearly_recur_years' => 'integer | digits_between: 1, 11',
                'yearly_day' => 'integer | digits_between: 1, 11',
                'yearly_ordinal' => 'integer',
                'yearly_month' => 'integer',
                'yearly_week_day' => 'integer'
            ]);
            $notificationDate = NotificationDate::findOrFail($id);
            $notificationDate->update($request->except(['app_id']));
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
            $notificationDate = NotificationDate::findOrFail($id);
            $notificationDate->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }
}
