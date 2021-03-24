<?php

namespace App\Http\Traits;
use App\AppFieldValueAppSettings as FieldAppSettings;
use App\AppFieldValueAttachment as FieldAttachment;
use App\AppFieldValueBoolean as FieldBoolean;
use App\AppFieldValueTrilean as FieldTrilean;
use App\AppFieldValueString as FieldString;
use App\AppFieldValueNumber as FieldNumber;
use App\AppFieldValueDate as FieldDate;
use App\AppFieldValueTag as FieldTag;
use App\AppFields;

trait GetValuesTrait {
    public function getValues(int $recordID, $fieldsValues) {
        try{
            // $fieldsValues = AppFields::where('panel_id', $panelID)
            // // ->join('field_value_date', function ($join) {
            // //     $join->on('type', '"timestamp"');
            // // })
            // ->get();

            $res = [];
            $typesToIds = []; // relationship between types and ids
            // $typesToIds is a map of $typesToIds[fieldID] = [ valueID ]
            // this is to keep track later on which ones are deleted etc on the frontend

            foreach ($fieldsValues as $key => $field) {
                $isReferencedField = $field->type === 'referenced';
                if ($isReferencedField) {
                    $field = $field->referenced_field()->first();
                }
                $type = $this->getType($field->type);
                // 'timestamp' => true,
                $map = [ 'people' => true, 'autocomplete' => true ];
                $result = isset($map[$field->type]) ? $type::where('field_id', $field->id)->where('record_id', $recordID)->get()
                    : ($field->type === 'attachment' ? $type::where('field_id', $field->id)->where('record_id', $recordID)->with('attachment')->get()
                    : $type::where('field_id', $field->id)->where('record_id', $recordID)->first());

               if(!$result) continue; // if result in non-existent/null then just skip

                $getValue = function ($v){ return $v["value"]; };
                $getIds = function ($v){ return [ 'id' => $v["id"], 'value' => $v["value"]]; };

                $key = $field->machine_name; // id

                switch (true) {
                  case $field->type == 'people':
                    $typesToIds[$field->id] = array_map($getIds, $result->toArray());
                    $res[$key] = array_map( $getValue, $result->toArray() );
                    break;

                  case $field->type == 'autocomplete':
                    $typesToIds[$field->id] = array_map($getIds, $result->toArray());
                    $res[$key] = $result->toArray();
                    break;

                  case $field->type == 'attachment':
                    $res[$key] = is_array($result) ? $result[0][0]['attachment'][0] : $result[0]['attachment'][0];
                    break;

                  default:
                    $res[$key] = is_array($result) ? $result[0]["value"] : $result["value"];
                    break;
                }

            }
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // return response()->json( [
        //     'values' => (object) $res,
        // ], 200);
        return $res;
    }

    public function getType($typeStr) {
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
            case 'trilean':
                $type = new FieldBoolean;
                break;
            case 'boolean':
                $type = new FieldTrilean;
                break;
            case 'number':
                $type = new FieldNumber;
                break;
            case 'text':
                $type = new FieldString;
                break;
            default:
                throw new \Exception("Type undefined.", 400);
        }
        return $type;
    }
}