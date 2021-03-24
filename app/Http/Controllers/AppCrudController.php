<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\AppFieldValueAppSettings as FieldAppSettings;
use App\AppFieldValueAttachment as FieldAttachment;
use App\AppFieldValueBoolean as FieldBoolean;
use App\AppFieldValueTrilean as FieldTrilean;
use App\AppFieldValueString as FieldString;
use App\AppFieldValueNumber as FieldNumber;
use App\AppFieldValueDate as FieldDate;
use App\AppFieldValueTag as FieldTag;
use App\AppFieldValueAddress as FieldAddress;
use App\FieldValueReferencedRecord as FieldRecord;
use App\AppFieldValueTaxonomy as FieldValueTaxonomy;

use App\AppFields;
use App\AppAttachments;
use App\AppRecords;
use App\AppTableRows;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class AppCrudController extends Controller {

    /**
     * Get one field with value by id and record_id
     *
     * @param int $redordID
     * @param int $fieldID
     *
     * @return Response
     */
    public function getValue($redordID, $fieldID){
        try{
            $field = AppFields::where('id', $fieldID)->first();
            $type = $this->getType($field['type']);
            // TODO: Refactor this.
            if ($field->type === 'timepstamp') {
                $field['value'] = $type::where('record_id', $redordID)->get();
            } else if ($field->type === 'people') {
                $field['value'] = $type::where('record_id', $redordID)->with('user')->get();
            } else {
                $field['value'] = $type::where('record_id', $redordID)->first();
            }

            return response()->json($field, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get one field with value by id and record_id
     *
     * @param int $record
     * @param int $fieldID
     *
     * @return Response
     */
    public function getAndChangeRefFieldValue($currentRecordID, $referenceRecordID, $fieldID, $refID){
        try{
            $field = AppFields::find($fieldID); //('id', $fieldID)->first();
            $type = $this->getType($field['type']);
            $response;
            $getValue = function ($v){ return $v["value"]; };
            // TODO: Refactor this.
            if ($field->type === 'timestamp') {
                $response = $type::where('record_id', $referenceRecordID)->get();
            } else if ($field->type === 'people') {
                $res = $type::where('record_id', $referenceRecordID)->get();// ->with('user')
                $val = array_map( $getValue, $res->toArray() );
                $response = [ 'value' => $val ];
            } else {
                $response = $type::where('record_id', $referenceRecordID)->first();
            }

            FieldRecord::updateOrCreate(
                [ 'record_id' => $currentRecordID, 'field_id' => $refID ],
                [ 'referenced_record_id' =>  $referenceRecordID, 'referenced_field_id' =>  $fieldID ]
            );

            return response()->json($response, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function oldGetValues(int $recordID, int $panelID){
      try{
          $fieldsValues = AppFields::where('panel_id', $panelID)
              // ->join('field_value_date', function ($join) {
              //     $join->on('type', '"timestamp"');
              // })
              ->get();

          $res = [];
          $typesToIds = []; // relationship between types and ids
          // $typesToIds is a map of $typesToIds[fieldID] = [ valueID ]
          // this is to keep track later on which ones are deleted etc on the frontend

          foreach ($fieldsValues as $key => $field) {
              $isReferencedField = $field->type === 'referenced';
              if ($isReferencedField) {
                  $referenced_record_id = $field->referenced_record_id;
                  $ref_field_id = $field->id;
                  $field = $field->referenced_field()->first();
                  $field->referenced_record_id = $referenced_record_id;
                  $field->ref_field_id = $ref_field_id;
              }
              $isReferencedApp = $field->type === 'referencedToApp';
              if ($isReferencedApp) {
                  $field = $field->referenced_app()->first();
              }

              $type = $this->getType($field->type);
              // 'timestamp' => true,
              $map = [ 'people' => true, 'autocomplete' => true ];
              $result = isset($map[$field->type]) ? $type::where('field_id', $field->id)->where('record_id', $recordID)->get()
                  : ($field->type === 'attachment' ? $type::where('field_id', $field->id)->where('record_id', $recordID)->with('attachment')->get()
                      : (isset($field->referenced_record_id) ? $type::where('field_id', $field->id)->where('record_id', $field->referenced_record_id)->first()
                      : $type::where('field_id', $field->id)->where('record_id', $recordID)->first()));

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
              switch (true) {
                  case $field->type == 'people':
                      $typesToIds[$field->id] = array_map($getIds, $result->toArray());
                      $res[$fieldIdToUse] = array_map( $getValue, $result->toArray() );
                      break;

                  case $field->type == 'autocomplete':
                      $typesToIds[$field->id] = array_map($getIds, $result->toArray());
                      $res[$fieldIdToUse] = $result->toArray();
                      break;

                  case $field->type == 'attachment':
                      $res[$fieldIdToUse] = is_array($result) ? $result[0][0]['attachment'][0] : $result[0]['attachment'][0];
                      break;

                  case $field->type == 'autocomplete-address':
                      $res[$fieldIdToUse] = [ 'value' => $result["value"], 'lat' => $result["lat"], 'lng' => $result["lat"] ];
                      break;

                  default:
                      $res[$fieldIdToUse] = is_array($result) ? $result[0]["value"] : $result["value"];
                      break;
              }

          }
      }catch(\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
      }

      return response()->json( [
          'values' => (object) $res,
          'typesToIds' => (object) $typesToIds
      ], 200);
  }



    /**
     * Get all values by recordID and panelID
     *
     * @param Request $request
     * @param int $recordID
     * @param $panelID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getValues(Request $request, int $recordID, $panelID){
        try{
            $fieldsValues;
            if( (int) $panelID ) {
                $fieldsValues = AppFields::where('panel_id', $panelID)
                    // ->join('field_value_date', function ($join) {
                    //     $join->on('type', '"timestamp"');
                    // })
                    ->get();
            } else {
                $ids = $request->ids;
                $fieldsValues = AppFields::whereIn('id', $ids)->get();
            }

            $res = [];
            $typesToIds = []; // relationship between types and ids
            // $typesToIds is a map of $typesToIds[fieldID] = [ valueID ]
            // this is to keep track later on which ones are deleted etc on the frontend
            $ref_ids = [];
            $ref_fields_ids = [];


            foreach ($fieldsValues as $key => $field) {
                $isReferencedField = $field->type === 'referenced';
                if ($isReferencedField) {
                    $referenced = FieldRecord::where('record_id', '=', $recordID)->where( 'field_id', '=', $field->id )->first();
                    $ref_field_type = AppFields::where( 'id', '=', $referenced["referenced_field_id"])->select('type')->first();

                    $ref_id = $field->id;
                    $field->ref_field_id = $referenced["referenced_field_id"];
                    $field->ref_id = $ref_id;
                    $field->referenced_record_id = $referenced["referenced_record_id"];
                    $field->type = $ref_field_type->type;
                    $ref_ids[$ref_id] = $referenced["referenced_record_id"];
                    $ref_fields_ids[$ref_id] = $referenced["referenced_field_id"];
                }

                $isReferencedApp = $field->type === 'referencedToApp';
                if ($isReferencedApp) {
                    $field = $field->referenced_app()->first();
                }

                $type = $this->getType($field->type);
                // 'timestamp' => true,
                $map = [ 'people' => true, 'autocomplete' => true ];
                $field_id = isset($field->ref_field_id) ? $field->ref_field_id : $field->id;
                $record_id = isset($field->referenced_record_id) ? $field->referenced_record_id : $recordID;
                $result = isset($map[$field->type]) ? $type::where('field_id', $field_id)->where('record_id', $record_id)->get()
                    : ($field->type === 'attachment' ? $type::where('field_id', $field_id)->where('record_id', $record_id)->with('attachment')->get()
                        : (isset($field->referenced_record_id) ? $type::where('field_id', $field->ref_field_id)->where('record_id', $field->referenced_record_id)->first()
                        : $type::where('field_id', $field->id)->where('record_id', $recordID)->first()));


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
                switch (true) {
                    case $field->type == 'people':
                        $typesToIds[$fieldIdToUse] = array_map($getIds, $result->toArray());
                        $res[$fieldIdToUse] = array_map( $getValue, $result->toArray() );
                        break;

                    case $field->type == 'autocomplete':
                        $typesToIds[$fieldIdToUse] = array_map($getIds, $result->toArray());
                        $res[$fieldIdToUse] = $result->toArray();
                        break;

                    case $field->type == 'attachment':
                        $res[$fieldIdToUse] = is_array($result) ? $result[0][0]['attachment'][0] : $result[0]['attachment'][0];
                        break;

                    case $field->type == 'autocomplete-address':
                        $res[$fieldIdToUse] = [ 'value' => $result["value"], 'lat' => $result["lat"], 'lng' => $result["lat"] ];
                        break;

                    default:
                        $res[$fieldIdToUse] = is_array($result) ? $result[0]["value"] : $result["value"];
                        break;
                }

            }
        }catch(\Exception $e) {
          error_log(json_encode($e));
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json( [
            'values' => (object) $res,
            'typesToIds' => (object) $typesToIds,
            'referenceRecordsIds' => (object) $ref_ids,
            'referenceFieldsIds' => (object) $ref_fields_ids
        ], 200);
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

    /**
     * Stores or updates values in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFieldValues(Request $request) {
        try {
            $this->validate($request, [
                'fields.*.record_id'   =>   'required | integer',
                'fields.*.field_id'    =>   'required | integer',
                'fields.*.value'       =>   'required',
                'below_subtotals'      => 'boolean'
            ]);
            if (isset($request->row_id)) {
              AppTableRows::find($request->row_id)->update(['below_subtotals' => $request->below_subtotals]);
            }
              error_log(json_encode($request->fields));

            foreach ($request->fields as $fieldQuery) {
                $field = AppFields::where('id', $fieldQuery[ 'field_id' ])->first();

                if ( isset($field[ 'type' ]) ) {

                    // $fieldQuery['record_id']= $request->record_id;
                    $this->saveValue($field->type, $fieldQuery);  // herehere
                }
            }
        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 500);
        }
        return response()->json([
            'message' => 'Successful'
        ], 201);
    }

    /**
     * Update one value in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function updateSigleValue($type, Request $request){
        try{
            $this->validate($request, [
                'record_id'   =>   'required | integer',
                'field_id'    =>   'required | integer',
                'value'       =>   'required'
            ]);
            $this->saveValue($type, $request);
        }catch(\Exception $e){
            return response()->json([ 'error' => $e->getMessage() ]);
        }
        return response()->json(["message" => "Success"], 200);
    }

    /**
     * Delete one Value
     *
     * @param string $type
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     */
    public function sampleValueDelete($type, $id){
        try{
            switch ($type) {
                case 'text':
                    $fieldValue = FieldString::findOrFail($id);
                    break;
                case 'number':
                    $fieldValue = FieldNumber::findOrFail($id);
                    break;
                case 'timestamp':
                    $fieldValue = FieldDate::findOrFail($id);
                    break;
                case 'people':
                    $fieldValue = FieldAppSettings::findOrFail($id);
                    break;
                case 'autocomplete':
                    $fieldValue = FieldTag::findOrFail($id);
                    break;
                case 'attachment':
                    $fieldValue = FieldAttachment::findOrFail($id);
                    break;
                case 'boolean':
                    $fieldValue = FieldBoolean::findOrFail($id);
                    break;
                case 'trilean':
                    $fieldValue = FieldTrilean::findOrFail($id);
                    break;
                default:
                    throw new \Exception("Type undefined.", 400);
            }
            $fieldValue->delete();
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], $e->getCode());
        }
        return response()->json(['message' => "The value has been deleted" ], 200);
    }

    /**
     * Delete values by ID group, you need send all arrays, you can send the arrays emptys
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function deleteSomeValues(Request $request){
        try{
            $this->validate($request, [
                'autocomplete' => 'present | array',
                'timestamp'    => 'present | array',
                'boolean'      => 'present | array',
                'trilean'      => 'present | array',
                'people'       => 'present | array',
                'number'       => 'present | array',
                'text'         => 'present | array'
            ]);

            if (count($request->autocomplete) > 0) {
                FieldTag::whereIn('id', $request->autocomplete)->delete();
            }
            if (count($request->timestamp) > 0) {
                FieldDate::whereIn('id', $request->timestamp)->delete();
            }
            if (count($request->boolean) > 0) {
                FieldBoolean::whereIn('id', $request->boolean)->delete();
            }
            if (count($request->trilean) > 0) {
                FieldTrilean::whereIn('id', $request->trilean)->delete();
            }
            if (count($request->people) > 0) {
                FieldAppSettings::whereIn('id', $request->people)->delete();
            }
            if (count($request->number) > 0) {
                FieldNumber::whereIn('id', $request->number)->delete();
            }
            if (count($request->text) > 0) {
                FieldString::whereIn('id', $request->text)->delete();
            }
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], $e->getCode());
        }
        return response()->json([ 'message' => "The values have been deleted." ], 200);
    }

    /**
     * You can delete all values by the fieldID
     *
     * @param int $fieldId
     *
     */
    public function deleteValuesByFieldId($fieldId){
        try {
            $res = FieldTag::         where('field_id', $fieldId)->delete();
            $res = FieldDate::        where('field_id', $fieldId)->delete();
            $res = FieldBoolean::     where('field_id', $fieldId)->delete();
            $res = FieldTrilean::     where('field_id', $fieldId)->delete();
            $res = FieldAppSettings:: where('field_id', $fieldId)->delete();
            $res = FieldNumber::      where('field_id', $fieldId)->delete();
            $res = FieldString::      where('field_id', $fieldId)->delete();
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ]);
        }
        return response()->json([ 'message' => 'Successfully deleted' ]);
    }

    public function deleteValueByFieldIds(Request $request) {
        try {
            $this->validate($request, [
                'deleteArr' => 'required'
            ]);

            $deleteArr = request('deleteArr');
            $getIds = function($v) { return $v["id"]; };
            foreach ($deleteArr as $key => $v) {
                $type = $this->getType($v["fieldType"]);
                $type->whereIn('id', array_map($getIds, $v["values"] ))->delete();
            }

        } catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ]);
        }

        return response()->json([ 'message' => 'Successfully deleted' ]);
    }

    public function getFile($fileId) {
        try{
            $file = AppAttachments::findOrFail($fileId);

            if (Storage::disk('public')->exists($file->file_path)) {
                $getFile = Storage::disk('public')->get($file->file_path);

                return response($getFile)->header('Content-Type', $file->file_type);
            }else {
                return response()->json(false);
            }
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 500);
        }
    }

    public function getPostFileUrls($postId) {
        try{
            $urls = $this->getStreamFiles('post', $postId);

            return response()->json($urls);
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 500);
        }
    }

    public function getMessageFileUrls($messageId) {
        try{
            $urls = $this->getStreamFiles('message', $messageId);

            return response()->json($urls);
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 500);
        }
    }

    public function setFile(Request $request) {
        try{
            if($request->headers->get('Content-Length') < 50000000){
                $parts = explode('/', $request->headers->get('Content-Type'));

                $globalPath = pathinfo(storage_path('app/public/app-builder/'.$parts[0].'/'.$request->headers->get('Content-Name')));
                $path = '/app-builder/'.$request->headers->get('path').$parts[0].'/';

                $data = array(
                    "file_size"      => $request->headers->get('Content-Length'),
                    "file_extension" => $globalPath['extension'],
                    "file_name_full" => $globalPath['basename'],
                    "file_type"      => $request->headers->get('Content-Type'),
                    "file_name"      => $globalPath['filename'],
                    "file_path"      => 'app-builder/'.$request->headers->get('path').$parts[0].'/'.$request->headers->get('Content-Name'),
                    "file_url"       => Storage::disk('public')->url($path.$globalPath['basename'])
                );

                Storage::disk('public')
                    ->put($path.$globalPath['basename'], $request->getContent());

                $attach = AppAttachments::create($data);

                return response()->json(["attachId" => $attach['id']], 201);
            }
        }catch (\Exception $e){
            return response()->json([ 'error' => $e->getMessage()], 500);
        }
    }

    public function storeStreamFiles(Request $request) {
        try {
            if($request->headers->get('Content-Length') < 50000000){
                $streamId = $request->headers->get('Stream-Id');
                $streamType = $request->headers->get('Stream-type');
                $parts = explode('/', $request->headers->get('Content-Type'));
                $globalPath = pathinfo(storage_path('/'.$request->headers->get('Content-Name')));

                $path = $streamType.'/'.$streamId.'/'.$parts[0].'/'.$globalPath['basename'];

                Storage::disk('s3')
                    ->put($path, $request->getContent());

                return response()->json(["attachUrl" => Storage::disk('s3')->url($path)], 201);
            }
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * gets all of the values for N number of fields using only its id and type
     * returns an associative array/json object. where the referenced field id
     * is the key and the content is an array of that specific fields values
     */
    public function listOfRecordsByApp(Request $request) {
        try {
            $this->validate($request, [
                'fields.*.id'    =>   'required | integer',
                'fields.*.type'  =>   'required',
                'fields.*.app_id'=>   'required'
            ]);

            $fields = (array) $request->fields;
            $fieldValues = [];

            foreach ($fields as $key => $field) {
                $results = AppRecords::where('app_id', '=', $field["app_id"])->get();
                $fieldValues[$field["id"]] = $results;
            }

            return $fieldValues;
        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    protected function getStreamFiles( $type, $streamId ) {
        $urls = array();
        $files = AppAttachments::where(
            'file_path', 'like', '%'.$type.'/'. $streamId . '%'
        )->get();

        foreach ($files as $key => $file) {
            array_push($urls, $file['file_url']);
        }

        return $urls;
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
            case 'calculated':
                $type = new FieldString;
                break;
            case 'taxonomy':
                $type = new FieldValueTaxonomy;
            default:
                throw new \Exception("Type undefined.", 400);
        }
        return $type;
    }

    protected function saveAttachment($data) {
        try{
            $attachment = new Request($data);

            $this->validate($attachment, [
                'file_size'      => 'required | integer| digits_between: 1, 8',
                'file_extension' => 'required | string | max:  10',
                'file_name_full' => 'required | string | max: 255',
                'file_type'      => 'required | string | max: 255',
                'file_name'      => 'required | string | max: 255'
            ]);

            $parts = explode('/', $attachment->file_type);
            $attachment['file_path'] = storage_path('app/app-builder/'.$parts[0]);
            $attachment['file_url'] = "";

            $attach = AppAttachments::create($attachment->all());

            return $attach;
        }catch(\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 500);
        }
    }

    /**
     * Protected function to save or update a value in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    protected function saveValue($type, $value){
        try{
            $validateArray = [];
            $cValue = gettype($value) === 'array' ? new Request($value) : $value;
            $values = [];
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
                    $validateArray[] = [ 'value' => 'integer' ];
                    $state = new FieldTrilean;
                    break;
                // TODO: need to create model for these types and need to change with them
                case 'referencedToApp':
                    $validateArray[] = [ 'value' => 'string' ];
                    $state = new FieldString;
                    break;
                // TODO: need to create model for these types and need to change with them
                case 'referenced':
                    $validateArray[] = [ 'value' => 'string' ];
                    $state = new FieldString;
                    break;
                case 'autocomplete-address':
                    $validateArray[] = [ 'value' => 'string', 'lat' => 'string', 'lng' => 'string' ];
                    $values = [ 'lat' => $cValue->value["lat"], 'lng' => $cValue->value["lng"] ];
                    $cValue->value = $cValue->value['value'];
                    $state = new FieldAddress;
                    break;
                case 'taxonomy':
                    $validateArray[] = [ 'value' => 'integer' ];
                    $state = new FieldValueTaxonomy;
                    break;
                default:
                    throw new \Exception("Type undefined.", 400);
            }

            $foreign_keys = [ 'record_id' => $cValue->record_id, 'field_id' => $cValue->field_id ];
            if( isset($cValue->table_row_id) ) $foreign_keys['table_row_id'] = $cValue->table_row_id;

            $values["value"] = $cValue->value;
            $this->validate( $cValue, $validateArray);
            $state::updateOrCreate(
                $foreign_keys,
                $values
            );
        }catch(\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
