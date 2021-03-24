<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Dependencies;
use App\Http\Traits\GetValuesTrait;

class SearchController extends Controller {
    use GetValuesTrait;

    public function filterRecords(Request $request) {
        try {
            $this->validate($request, [
                "appId" => 'required|integer',
                "filterData" => 'required', 
                "fieldsList" => 'required'
            ]);

            [ 
              'appId' => $app_id, 
              'filterData' => $filter_data, 
              'fieldsList' => $fields_list 
            ] = $request->only([ 'appId', 'filterData', 'fieldsList' ]);

            $results = [];

            foreach ($fields_list as $key => $field) {
                if( !isset($filter_data[$field["id"]])) continue;

                $type = $this->getType($field["type"]);
                
                $partialQuery;

                switch (true) {
                    case is_array($filter_data[$field["id"]]) :
                        $partialQuery = $type::wherein('value', $filter_data[$field["id"]]);
                        break;
                    
                    default:
                        $partialQuery = $type::where('value', $filter_data[$field["id"]]);
                        break;
                }

                $initial_result = $partialQuery->select('record_id')->get();
                $get_record_ids = function($val){ return $val["record_id"]; };
                $processed_ids = array_map($get_record_ids, $initial_result->toArray());

                array_push($results, $processed_ids);
            }
            
            $ids_hash = [];
            foreach ($results as $key_1 => $res) {
                foreach ($res as $key_2 => $id) {
                    !isset($ids_hash[$id]) ? $ids_hash[$id] = 1 : $ids_hash[$id]++;
                }
            } 
            $record_ids = [];

            foreach ($ids_hash as $key => $qty) {
                if( $qty == count($fields_list) ) array_push($record_ids, $key);
            }

            return $record_ids;
        } catch(\Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], 400);
        }
    }

}
