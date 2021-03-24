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
use Illuminate\Http\Request;

trait StoreFieldValuesTrait {

    public function bulkSaveFields($payload) {
       try {
            foreach ($payload["fields"] as $fieldQuery) {
                $field = AppFields::where('id', $fieldQuery[ 'field_id' ])->first();

                if ( isset($field[ 'type' ]) ) {

                    $fieldQuery['record_id']= $payload["record_id"];
                    $this->saveValue($field->type, $fieldQuery);
                }
            }

            return [ 'success' => true];
        } catch(\Exception $e) {
           return $e;
        }
    }

    protected function saveValue($type, $value){
        try{
            $validateArray = [];
            $cValue = gettype($value) === 'array' ? new Request($value) : $value;

            switch ($type) {
                case 'text':
                    $validateArray[] = [ 'value' => 'string' ];
                    $state = new FieldString;
                    break;
                case 'number':
                    $validateArray[] = [ 'value' => 'Numeric' ];
                    $state = new FieldNumber;
                    $cValue->value = floatval($value['value']);
                    break;
                case 'timestamp':
                    $validateArray[] = [ 'value' => 'date' ];
                    $state = new FieldDate;
                    break;
                case 'attachment':
                    $validateArray[] = [ 'value' => 'integer' ];
                    $cValue->value = $cValue->value['value'];
                    $state = new FieldAttachment;
                    break;
                case 'people':
                    $this->validate( $cValue, [ 'value' => 'string' ]);
                    FieldAppSettings::create($cValue->all());
                    return;
                case 'autocomplete':
                    $this->validate( $cValue, [ 'value' => 'string' ]);
                    FieldTag::create($cValue->all());
                    return;
                case 'boolean':
                    $validateArray[] = [ 'value' => 'boolean' ];
                    $state = new FieldBoolean;
                    break;
                case 'trilean':
                    $validateArray[] = [ 'value' => 'trilean' ];
                    $state = new FieldTrilean;
                    break;
                default:
                    throw new \Exception("Type undefined.", 400);
            }
            $this->validate( $cValue, $validateArray);
            $state::updateOrCreate(
                [ 'record_id' => $cValue->record_id, 'field_id' => $cValue->field_id ],
                [ 'value' => $cValue->value ]
            );
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

}