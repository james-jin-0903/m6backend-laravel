<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\ContactNotification;
use App\NotificationDate;
use App\Notifications;

class NotificationController extends Controller {
    /**
     * Display a listing of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try{
            $notifications = Notifications::where('deleted_at',null)->with(['notificationDate', 'notiCont'])->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($notifications, 200);
    }
    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNotification(Request $request) {
        try {
            $this->validate($request, [
                // Notification
                'notification.notification_required' => 'required | boolean',
                'notification.name' => 'required | string | max:255',
                'notification.app_id' => 'required|integer',
                'notification.description' => 'string',
                // Date
                'noti_date.weekly_recur_every_x_week' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.daily_every_weekday' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.monthly_every_month' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.yearly_recur_years' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.daily_every_x_day' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.monthly_day' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.yearly_day' => 'integer | digits_between: 1, 11 | nullable',
                'noti_date.recurrence' => 'string | max: 255 | nullable',
                'noti_date.yearly_week_day' => 'integer | nullable',
                'noti_date.monthly_ordinal' => 'integer | nullable',
                'noti_date.yearly_ordinal' => 'integer | nullable',
                'noti_date.monthly_month' => 'integer | nullable',
                'noti_date.yearly_month' => 'integer | nullable',
                'noti_date.date' => 'required | date'
            ]);

            $notificationDate = $this->postNotificationDate($request['noti_date']);

            $noti = $request->notification;
            $noti['date'] = $notificationDate->id;
            $notification = Notifications::create($noti);
            $notiCont = $this->storeNotificationsContact($request['noti_cont'], $notification->id);

            $notification['notification_date'] = $notificationDate;
            $notification['noti_cont'] = $notiCont;
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($notification, 201);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByID($id) {
        try {
            $response = Notifications::where('id',$id)->with('notificationDate','notiCont')->first();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }

    /**
     * Display the specified group of notifications.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByAppID($appId) {
        try {
            $response = Notifications::where('app_id',$appId)->with(['notificationDate','notiCont'])->get();
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
    public function updateNotification(Request $request, $id) {
        $this->validate($request, [
            'notification.notification_required' => 'boolean',
            'notification.name' => 'string | max:255',
            'notification.description' => 'string | nullable',
            // Date
            'noti_date.weekly_recur_every_x_week' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.daily_every_weekday' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.monthly_every_month' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.yearly_recur_years' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.daily_every_x_day' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.monthly_day' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.yearly_day' => 'integer | digits_between: 1, 11 | nullable',
            'noti_date.recurrence' => 'string | max: 255 | nullable',
            'noti_date.yearly_week_day' => 'integer | nullable',
            'noti_date.monthly_ordinal' => 'integer | nullable',
            'noti_date.yearly_ordinal' => 'integer | nullable',
            'noti_date.monthly_month' => 'integer | nullable',
            'noti_date.yearly_month' => 'integer | nullable',
            'noti_date.id' => 'required | integer',
            'noti_date.date' => 'date'
        ]);
        try{
            $notification = Notifications::findOrFail($id);
            $notification->update($request['notification']);
            $this->putNotificationDate($request['noti_date']);
            $res = $this->storeNotificationsContact($request['noti_cont'], $request['notification']['id']);
            $this->removeNotificationsContact($request['preview_noti_cont'], $request['noti_cont'], $request['notification']['id']);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($res, 200);
    }

    /**
     * Remove the specified record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $notification = Notifications::findOrFail($id);
            $notification->delete();

            return response()->json(['message' => "The record has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record was not found" ], 404);
        }
    }

    protected function postNotificationDate($notiDate){
        try{
            $res = NotificationDate::create($notiDate);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $res;
    }

    protected function putNotificationDate($notiDate){
        try{
            $res = NotificationDate::findOrFail($notiDate['id']);
            $res->update($notiDate);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $res;
    }

    protected function storeNotificationsContact($contNoti, $notiId) {
        try {
            foreach ($contNoti as $key => $value) {
                $contNoti[$key] = [
                    'contact_id' => $contNoti[$key]['id'],
                    'notification_id' => $notiId,
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ];
                unset ($contNoti[$key]['name']);
                ContactNotification::firstOrCreate([
                    'contact_id' => $contNoti[$key]['contact_id'],
                    'notification_id'=> $contNoti[$key]['notification_id']
                ], $value);
            }
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $contNoti;
    }

    protected function removeNotificationsContact($preview, $curState, $notiId){
        // return $preview;
        foreach ($curState as $key => $cur) {
            $pos = array_search($cur, $preview);
            if($pos>=0){
                unset($preview[$pos]);
            }
        }foreach($preview as $prev) {
            ContactNotification::where([
                ['notification_id', $notiId],
                ['contact_id', $prev['id']]
            ])->delete();
        }
    }
}
