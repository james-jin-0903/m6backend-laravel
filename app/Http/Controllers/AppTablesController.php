<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\AppFields;
use App\AppTables;
use App\AppTableRows;

use App\AppFieldValueAppSettings as FieldAppSettings;
use App\AppFieldValueAttachment as FieldAttachment;
use App\AppFieldValueBoolean as FieldBoolean;
use App\AppFieldValueTrilean as FieldTrilean;
use App\AppFieldValueString as FieldString;
use App\AppFieldValueNumber as FieldNumber;
use App\AppFieldValueDate as FieldDate;
use App\AppFieldValueTag as FieldTag;
use App\AppFieldValueAddress as FieldAddress;

class AppTablesController extends Controller {
    /**
     * creates the table record in the app_tables table
     * @param Request $request
     */
    public function create(Request $request) {
        try {
            $this->validate($request, [
                'title' => 'required|string',
                'app_id' => 'required|integer',
                'panel_id' => 'required|integer'
            ]);

            $table = new AppTables($request->only(['title', 'app_id', 'panel_id']));
            $table->save();
            return $table;
        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 400);
        }
    }

    public function update(Request $request, $table_id) {
        try {
            $this->validate($request, [
                'title' => 'required'
            ]);

            $table = AppTables::where('id', $table_id)->first();
            $table->title = $request['title'];
            $table->add_totals_row = $request['add_totals_row'];
            $table->add_sub_totals = $request['add_sub_totals'];
            $table->save();

            return [ 'message' => 'successful', 'request' => $request->all() ];
        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 400);
        }
    }

    public function delete(Request $request, $table_id) {
        try {
            AppFields::where('table_id', $table_id)->delete();
            AppTables::find($table_id)->delete();

            return [ 'message' => 'successful' ];
        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 400);
        }
    }

    public function tableFields($table_id) {
        try {
            return AppFields::where('table_id', $table_id)->orderBy('created_at', 'ASC')->get();
        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 400);
        }
    }

    public function createRow(Request $request) {
        try {
            $this->validate($request, [
                'tableId' => 'required',
                'recordId' => 'required'
            ]);

            $table_row = new AppTableRows([ 'table_id' => request('tableId'), 'record_id' => request('tableId') ]);
            $table_row->save();
            return $table_row;
        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 400);
        }
    }

    public function getTableRowValues($table_id, $recordID) {
        $fieldsValues = AppFields::where('table_id', $table_id)->get();
        $table_rows = AppTableRows::where('table_id', $table_id)->orderBy('below_subtotals', 'asc')->get();
        $exist_above_sub = AppTableRows::where('table_id', $table_id)->where('below_subtotals', 0)->exists();
        $table = AppTables::where('id', $table_id)->first();
        $results = [];
        $resultCase = [];
        $types = [];
        $ref_res = [];
        $ids = [];
        $typesToIdsRes = [];
        try{
          $sub = [
            'total' => 0,
            'subtotal' => true,
            'title' => 'Sub Total',
            'metadata' => [
              'tableRowID' => 'none',
              'editable' => false
            ]
          ];
          $tot = [
              'total' => 0,
              'finalTotal' => true,
              'title' => 'Total',
              'metadata' => [
                  'tableRowID' => 'none',
                  'editable' => false
              ]
          ];
            $idx = 0;
            $sub_idx = 0;
            foreach ($table_rows as $row) {
                $res = [
                  'total' => 0
                ];

                $typesToIds = [];

                if(count($results) > 0 && $row->below_subtotals == true && $exist_above_sub == true) {
                  if($sub_idx == 0) {
                    $sub_idx = $idx;
                    $results[] = [];
                  }
                }

                foreach ($fieldsValues as $field) {
                    $isReferencedField = $field->type === 'referenced';
                    if ($isReferencedField) {
                        $referenced_record_id = $field->referenced_record_id;
                        $ref_field_id = $field->referenced_field;
                        $ref_id = $field->id;
                        $field = $field->referenced_field()->first();
                        $field->referenced_record_id = $referenced_record_id;
                        $field->ref_field_id = $ref_field_id;
                        $field->ref_id = $ref_id;
                    }
                    $isReferencedApp = $field->type === 'referencedToApp';
                    if ($isReferencedApp) {
                        $field = $field->referenced_app()->first();
                    }

                    $type = $this->getType($field->type);
                    $types[] = $type;

                    // 'timestamp' => true,
                    $map = [ 'people' => true, 'autocomplete' => true ];
                    $field_id = isset($field->ref_field_id) ? $field->ref_field_id : $field->id;
                    $record_id = isset($field->referenced_record_id) ? $field->referenced_record_id : $recordID;

                    $ids[] = $record_id;

                    if(isset($map[$field->type])) {
                      $resultCase[] = 'case 1';
                      $result = $type::where('field_id', $field_id)->where('record_id', $record_id)->where('table_row_id', $row->id)->get();
                    } else if($field->type === 'attachment') {
                      $resultCase[] = 'case 2';
                      $result = $type::where('field_id', $field_id)->where('record_id', $record_id)->where('table_row_id', $row->id)->with('attachment')->get();
                    } else if(isset($field->referenced_record_id)){
                      $resultCase[] = 'case 3';
                      $result = $type::where('field_id', $field->id)->where('record_id', $field->referenced_record_id)->where('table_row_id', $row->id)->first();
                    } else {
                      $resultCase[] = 'case 4';
                      $result = $type::where('field_id', $field->id)->where('record_id', $record_id)->where('table_row_id', $row->id)->first();
                    }
                    // $result = isset($map[$field->type]) ? $type::where('field_id', $field_id)->where('record_id', $record_id)->where('table_row_id', $row->id)->get()
                    //     : ($field->type === 'attachment' ? $type::where('field_id', $field_id)->where('record_id', $record_id)->where('table_row_id', $row->id)->with('attachment')->get()
                    //         : (isset($field->referenced_record_id) ? $type::where('field_id', $field->id)->where('record_id', $field->referenced_record_id)->where('table_row_id', $row->id)->first()
                    //         : $type::where('field_id', $field->id)->where('record_id', $recordID)->where('table_row_id', $row->id)->first()));

                    $ref_res[] = $result;

                    $resultType = gettype($result);

                    switch ($resultType) {
                        case 'object':
                            if (method_exists($result, 'isEmpty') && $result->isEmpty()) {
                                continue 2;
                            } else if (empty((array)$result)) {
                                continue 2;
                            }
                            break;
                        case 'NULL':
                            continue 2;
                        default:
                            break;
                    }

                    $getValue = function ($v){ return $v["value"]; };
                    $getIds = function ($v){ return [ 'id' => $v["id"], 'value' => $v["value"]]; };

                    $fieldIdToUse = $this->whichIdToUse($field);

                    switch ($field->type) {
                        case 'people':
                            $typesToIds[$fieldIdToUse] = array_map($getIds, $result->toArray());
                            $res[$fieldIdToUse] = array_map( $getValue, $result->toArray() );
                            break;

                        case 'autocomplete':
                            $typesToIds[$fieldIdToUse] = array_map($getIds, $result->toArray());
                            $res[$fieldIdToUse] = $result->toArray();
                            break;

                        case 'attachment':
                            $res[$fieldIdToUse] = is_array($result) ? $result[0][0]['attachment'][0] : $result[0]['attachment'][0];
                            break;

                        case 'autocomplete-address':
                            $res[$fieldIdToUse] = [ 'value' => $result["value"], 'lat' => $result["lat"], 'lng' => $result["lat"] ];
                            break;

                        default:
                            $res[$fieldIdToUse] = is_array($result) ? $result[0]["value"] : $result["value"];
                            break;

                    }

                    $res['total'] = is_numeric($res[$fieldIdToUse]) ? $this->calculateResult($res['total'], $res[$fieldIdToUse]) : $res['total'];

                    $tot[$fieldIdToUse] = isset($tot[$fieldIdToUse])
                        ? (is_numeric($res[$fieldIdToUse])
                            ? $this->calculateResult($tot[$fieldIdToUse], $res[$fieldIdToUse])
                            : $tot[$fieldIdToUse])
                        : $this->calculateResult(0, $res[$fieldIdToUse]);

                    $tot['total'] = is_numeric($res[$fieldIdToUse]) ? $this->calculateResult($tot['total'], $res[$fieldIdToUse]) : $tot['total'];

                    if($row->below_subtotals == false) {
                      $sub[$fieldIdToUse] = isset($sub[$fieldIdToUse])
                        ? (is_numeric($res[$fieldIdToUse])
                            ? $this->calculateResult($sub[$fieldIdToUse], $res[$fieldIdToUse])
                            : $sub[$fieldIdToUse])
                        : $this->calculateResult(0, $res[$fieldIdToUse]);
                      $sub['total'] = is_numeric($res[$fieldIdToUse]) ? $this->calculateResult($sub['total'], $res[$fieldIdToUse]) : $sub['total'];
                    } else {
                      if (!isset($sub[$fieldIdToUse])) {
                        $sub[$fieldIdToUse] = 0;
                      }
                    }
                }

                $res['metadata']['typesToIds'] = (object) $typesToIds;
                $res['metadata']['tableRowID'] = $row->id;
                $res['metadata']['belowSub'] = $row->below_subtotals;
                $results[] = $res;
                $idx = $idx + 1;
            }
            if(count($results) > 0) {
              if($table->add_sub_totals && $exist_above_sub) {
                $sub['metadata']['index'] = $sub_idx;
                if( $sub_idx  > 0 ) {
                  $results[$sub_idx] = $sub;
                } else {
                  $results[] = $sub;
                }
              }
              if($table->add_totals_row) {
                $results[] = $tot;
              }
            }
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
          'table_id' => $table_id,
          'results' => $results,
          'cases' => $resultCase,
          'refs' => $ref_res,
          'types' => $types,
          'ids' => $ids,
          'fields' => $fieldsValues,
          'rows' => $table_rows
        ], 200);
    }

    public function deleteRow($table_row_id) {
        try {
            AppTableRows::where('id', $table_row_id)->delete();
            return response()->json([ 'message' => 'success' ], 200);
        } catch( \Exception $e ) {
            return response()->json([ 'error' => $e->getMessage() ], 500);
        }
    }

    public function deleteTable($table_id) {
      try {
        AppTables::where('id', $table_id)->delete();
      } catch (\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
      }
    }

    protected function calculateResult($total, $attempt) {
      /**
       * Attempt to sum results, return prev result if error
       */
      $bc = $total;
      try {
        $n_total = $total + (float)($attempt);
        return (float)($n_total);
      } catch(\Exception $ex) {
        return $bc;
      }
    }

    protected function getType($typeStr) {
        switch ($typeStr) {
            case 'timestamp':
                $type = new FieldDate;
                break;
            case 'people':
                $type = new FieldAppSettings;
                break;
            case 'autocomplete':
                $type = new FieldTag;
                break;
            case 'attachment':
                $type = new FieldAttachment;
                break;
            case 'boolean':
                $type = new FieldBoolean;
                break;
            case 'trilean':
                $type = new FieldTrilean;
                break;
            case 'number':
                $type = new FieldNumber;
                break;
            case 'text':
                $type = new FieldString;
                break;
            case 'autocomplete-address':
                $type = new FieldAddress;
                break;
            case 'referencedToApp':
                $type = new FieldString;
                break;
            case 'referenced':
                $type = new FieldString;
                break;
            default:
                throw new \Exception("Type undefined.", 400);
        }

        return $type;
    }

     /**
     * returns the reference id or the field id depending on which one is needed
     *
     * in the case that it is a reference field we return the referencing field id,
     * as in the field record in mysql that is doing the referencing as that is the one being refered to
     * on the frontend. Else we just return the normal field id
     */
    private function whichIdToUse($field) {
        if(isset($field->ref_id)) return $field->ref_id;
        return $field->id;
    }


}
