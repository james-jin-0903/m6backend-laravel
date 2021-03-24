<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\M6Apps;
use App\Http\Traits\GetValuesTrait;

class AppRecordsController extends Controller {
    use GetValuesTrait;

    public function index($appPrefix = 'RAP') {
        try {
            $app = M6Apps::where('prefix', '=', $appPrefix)->with('records')->first(); // this is the Prefix for Rapid's backend
            $app->load('fields_panel');

            $listOfStandardFields = [ 
                'title', 'record_number', 'description', 'status', 
                'author', 'image', 'class', 'category', 'type', 'state'
            ];

            $res = [];

            if( !count((array) $app) || !count($app["records"]) ) return [];

            foreach ($app->records as $key => $record) {
                $result = $this->getValues($record->id, $app->fields_panel);
                $result["record_id"] = $record->id;
                $result["app_name"] = $app->title;
                $result["app_prefix"] = $appPrefix;

                foreach ($listOfStandardFields as $k => $standard_field) {
                    $result['standard_field_' . $standard_field] = $record[$standard_field];
                }

                array_push($res, $result);
            }

            return $res;
        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

}