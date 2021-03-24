<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\M6Apps;
use App\Http\Traits\StoreFieldValuesTrait;
use App\Http\Traits\GetValuesTrait;
use App\AppRecords;
use App\Http\Traits\RecordTrait;
use App\RecordsCount;
use Carbon\Carbon;

class RapidTicketController extends Controller {
    use StoreFieldValuesTrait;
    use GetValuesTrait;
    use RecordTrait;

    public function index() {
        try {
            $app = M6Apps::where('prefix', '=', 'RAP')->with('records')->first(); // this is the Prefix for Rapid's backend
            $app->load('fields_panel');

            $res = [];

            if( !count((array) $app) || !count($app["records"]) ) return [];

            foreach ($app->records as $key => $record) {
                $result = $this->getValues($record->id, $app->fields_panel);
                $result["record"] = $record;
                // the below status is not the record status but a quick way to access the rapid status
                // for the kanban board. please don't touch this, unless you know what your doing
                $result["status"] = isset($result["rapid_status"]) ? $result["rapid_status"] : 'none';
                $result["title"] = $record->title;
                $result["id"] = $record->id;
                array_push($res, $result);
            }

            return $res;
        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param Request $request
     */
    public function create(Request $request) {
        try {
            $this->validate($request, [
                'rapidTickets' => 'required'
            ]);

            $rapidTickets = request('rapidTickets');

            $app = M6Apps::where('prefix', '=', 'RAP')->with('fields_panel')->first(); // this is the Prefix for Rapid's backend

            foreach ($rapidTickets as $index => $ticket) {
                unset($ticket["id"]);
                $record_number = $this->getLatestRecordNumber($app["id"], 'RAP');

                $newAppRecord = new AppRecords;
                $newAppRecord->app_id = $app->id;
                $newAppRecord->record_number = $record_number;
                $newAppRecord->title = $ticket["rapid_title"];
                $newAppRecord->description = $ticket["rapid_description"];
                $newAppRecord->status = $ticket["rapid_status"];
                $newAppRecord->author = '';
                $newAppRecord->metadata = json_encode( (object) []);
                $newAppRecord->save();

                $this->updateOrCreateRecordCount($app["id"], Carbon::now()->year);


                $fieldsPayload = $this->createPayloadForFields($app->fields_panel, $ticket);

                $payload = [ 'record_id' => $newAppRecord->id, 'fields' => $fieldsPayload ];

                $this->bulkSaveFields($payload);
            }

            return [ 'success' => true];
        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()// 'There was an error while saving the ticket'
            ], 400);
        }
    }

    private function fieldsIdsToMachineNameMap($fields) {
        $res = [];

        foreach ($fields as $key => $v) {
            $res[$v["machine_name"]] = $v["id"];
        }

        return $res;
    }

    private function createPayloadForFields($fields, $ticket) {
        $idsToMachineNameMap = $this->fieldsIdsToMachineNameMap(  $fields);

        $res = [];

        foreach ($ticket as $key => $field_value) {
            if( isset($idsToMachineNameMap[$key]) ) {
                $res[] = [ 'field_id' => $idsToMachineNameMap[$key], 'value' => $field_value ];
            }
        }

        return $res;
    }

    public function updateOrCreateRecordCount($appId, $year) {

        $res = RecordsCount::where([ ['app_id', '=', $appId], ['year', '=', $year] ])->get();

        try {
            if( !count($res) ) {
                $newRecordsCount = new RecordsCount();
                $newRecordsCount->app_id = $appId;
                $newRecordsCount->year = $year;
                $newRecordsCount->count = 1;
                $newRecordsCount->save();
                return 1;
            } else {
                $recordCount = $res[0];
                $recordCount->count++;
                $recordCount->save();
                return $recordCount->count;
            }
        } catch(\Exception $e) {
            return $e;
        }
    }
}
