<?php

namespace App\Http\Controllers;

use App\AppsSettings;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
// Apps
use App\M6Apps;
// ITApps
use App\InformationSecurity;
use App\AppInfoGeneral;
use App\TagsModel;
use App\Image;
// DynamicApps
use App\AppRecords;
use App\AppTabs;
use App\AppPanels;

use App\AppFields;

// field values
use App\AppFieldValueAppSettings as FieldAppSettings;
use App\AppFieldValueAttachment as FieldAttachment;
use App\AppFieldValueBoolean as FieldBoolean;
use App\AppFieldValueTrilean as FieldTrilean;
use App\AppFieldValueString as FieldString;
use App\AppFieldValueNumber as FieldNumber;
use App\AppFieldValueDate as FieldDate;
use App\AppFieldValueTag as FieldTag;

class M6AppController extends Controller {

    /**
     * Displey filter Applications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterApps(Request $request) {
        try{
            $apps = M6Apps::where([
              ['title', 'like', '%'. $request->param . '%'], ['app_type', 'itapps']])->orWhere([
                  ['description', 'like', '%'. $request->param . '%'], ['app_type', 'itapps']])->with('imageInfo', 'generalInfo')->get()->toArray();

            $records = AppRecords::where([
              ['title', 'like', '%'. $request->param . '%']])->orWhere([
                  ['description', 'like', '%'. $request->param . '%']
              ])->get()->toArray();

            foreach ($records as $key => $value) {
                $records[$key]['app_type'] = 'dynamic_app';
            }
            return response()->json(array_merge($apps, $records), 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filterBuilderApps(Request $request) {
        try{
            $apps = M6Apps::where([
              ['title', 'like', '%'. $request->param . '%'], ['app_type', 'dynamic_app']])->orWhere([
                  ['description', 'like', '%'. $request->param . '%'], ['app_type', 'dynamic_app']
              ])->with('imageInfo')->get()->toArray();

            return response()->json($apps, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function selectApps() {
        try{
            $records = M6Apps::where('app_type', 'dynamic_app' )->select('id', 'prefix', 'title', 'iconLink', 'metadata')->with('fields')->get();
            return response()->json($records, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage(), 500]);
        }

    }

    public function searchApps(Request $request) {
        try{
            $records = M6Apps::where('title', 'like', '%'. $request->keyword . '%')
                              ->select('id', 'prefix', 'title', 'iconLink', 'metadata')
                              ->with('fields')
                              ->get();
            return response()->json($records, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage(), 500]);
        }

    }

    /**
     * Display a listing of the record.
     * @param string type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecords($type) {
        try{
            $records = M6Apps::where('app_type', $type )->get();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($records, 200);
    }
    /**
     * Display a listing of the record.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allItApps() {
        try{
            $apps = M6Apps::where('app_type','itapps')->with('imageInfo', 'generalInfo')->get();
            return response()->json($apps, 200);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allDynamicRecords() {
        try{
            $apps = AppRecords::with('app')->get();
            foreach ($apps as $app) {
                $app['app_type'] = 'dynamic_app';
                $fields = AppFields::where('app_id',$app->app_id)->where('show_in_table', true)->orderBy('table_index', 'ASC')->get();
                $app->fields = $fields;
            }
            return response()->json($apps, 200);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allDynamicApps() {
        try{
            $apps = M6Apps::where('app_type', 'dynamic_app')->get();
            return response()->json($apps, 200);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function DynamicAppsByID($id) {
        try {
            $apps = AppRecords::where('app_id', $id)->with(['app'])->get();
            foreach ($apps as $app) {
                $app['app_type'] = 'dynamic_app';
                $app["app_prefix"] = $app["app"]["prefix"];

                $fields = AppFields::where('app_id', $app->app_id)->where('show_in_table', true)->orderBy('table_index', 'ASC')->get();
                foreach($fields as $field) {
                  $field->value = $this->getFieldValue($field->id, $app->id, $field->type);
                }
                $app->fieldValues = $fields;
            }
            return response()->json($apps, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllApps() {
      try {
          $apps = M6Apps::with('imageInfo', 'generalInfo')->get()->toArray();
          $dynamicApps = AppRecords::with('app')->get()->toArray();
          $it_apps = [];
          foreach ($apps as $key => $v) {
              if($v['app_type'] === 'itapps') array_push($it_apps, $v);
          }

          foreach ($dynamicApps as $key => $value) {
              $dynamicApps[$key]['app_type'] = 'dynamic_app';
              $appIndex = array_search($value["app_id"], array_column($apps, 'id'));
              $dynamicApps[$key]["app_prefix"] = $apps[$appIndex]["prefix"];
          }
          return response()->json( array_merge($it_apps, $dynamicApps), 200);
      } catch(\Exception $e){
          return response()->json(['error' => $e->getMessage()]);
      }
    }

    /**
     * Return the record key.
     *
     * @param $m6key
     * @return String
     */
    protected function makeKey($m6key) {
        $appCount = M6Apps::where('app_type',$m6key)->count();
        $appNumber = str_pad($appCount+1, 8, '0', STR_PAD_LEFT);
        switch ($m6key) {
            case 'itapps':
                return 'ITA-'.$appNumber.'-'.date('Y');
            case 'dynamic_app':
                return 'DYA-'.$appNumber.'-'.date('Y');
            default:
                return false;
        }
    }

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function storeApp(Request $request) {
        try {
            $this->validate($request, [
                'title' => 'required|string|max:255',
                'description' => 'required|string'
            ]);
            $allValues = $request->all();
            $allValues['iconLink'] = '';
            $app = M6Apps::create($allValues);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $app;
    }

    /**
     * Display the specified group of records or record.
     *
     * @param  $column
     * @param  $value
     * @return \Illuminate\Http\Response
     */
    public function showItAppsByConsult($column, $value) {
        try{
            $response = M6Apps::where([
                [$column,'=', $value],
                ['app_type','=', 'itapps'],
            ])->get();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }

    /**
     * Display the specified app with the specification info.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSpecificationInfo($id) {
        try {
            $ITApp = M6Apps::where([
                ['id',$id],
                ['app_type','=', 'itapps'],
            ])->with([
                'specificationCertification.type', 'specificationMaintenance', 'specificationMonitoring'
            ])->first();
        } catch(QueryException $e) {
            return response( $e->getMessage(), 501);
        }
        return response()->json($ITApp, 200);
    }

    /**
     * Display the specified app with general information, image and security information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllITAppInfo($id) {
        try {
            $ITApp = M6Apps::where([
                ['id',$id],
                ['app_type','=', 'itapps']
            ])->with(['generalInfo', 'informationSecurity',
                'imageInfo', 'alsoKnown', 'formerlyKnown'
            ])->first();
        } catch(QueryException $e) {
            return response( $e->getMessage(), 501);
        }
        return response()->json($ITApp, 200);
    }

    /**
     * Display the specified app with installation atachment, installation support installation general info
     * and aditional information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllInstallInfo($id) {
        try {
            $ITApp = M6Apps::where([
                ['id',$id],
                ['app_type','=', 'itapps'],
            ])->with([
                'installationAttachment', 'installationSupport', 'installationGeneral', 'installationAditionalInformation'
            ])->first();
        } catch(QueryException $e) {
            return response( $e->getMessage(), 501);
        }
        return response()->json($ITApp, 200);
    }

    /**
     * Display the specified app with the rationalization info.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllRationalizationInfo($id) {
        try {
            $ITApp = M6Apps::where([
                ['id',$id],
                ['app_type','=', 'itapps'],
            ])->with([
                'rationalizationAttributes', 'rationalizationCosts',
                'rationalizationFte.type', 'rationalizationGovernance',
                'rationalizationLicensing', 'rationalizationUsers.type'
            ])->first();
        } catch(QueryException $e) {
            return response( $e->getMessage(), 501);
        }
        return response()->json($ITApp, 200);
    }

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByID($id) {
        try {
            $response = M6Apps::where([
                ['id',$id],
                ['app_type','=', 'itapps']
            ])->first();
        } catch(QueryException $e) {
            return response( $e->getMessage(), 501);
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
    public function updateITApp(Request $request, $id) {
        try{
            $this->validate($request, [
                'title' => 'string|max:255',
                'description' => 'string'
            ]);

            $ITApp = M6Apps::findOrFail($id);
            $ITApp->update($request->only(['title', 'description']));
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The record has been updated' ], 200);
    }

    /**
     * Store a newly created record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postItApp(Request $request) {
        try{
            $this->validate($request, [
                // ItApp
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                // 'General Ifo'
                'general_ifo.first_contact_group_settings_id' => 'required|integer',
                'general_ifo.server_hosting_model_settings_id'  => 'integer',
                'general_ifo.status_settings_id' => 'required|integer',
                'general_ifo.app_management_settings_id' => 'integer',
                'general_ifo.vendor_id' => 'required|string|max:255',
                'general_ifo.sub_category_settings_id' => 'integer',
                'general_ifo.version' => 'required|string|max:255',
                'general_ifo.category_settings_id' => 'integer',
                'general_ifo.type_settings_id' => 'integer',
                'general_ifo.capabilities' => 'integer',
                // Information Security
                'information_security.facing' => 'required | boolean',
                'information_security.ssn' => 'required | integer',
                'information_security.phi' => 'required | boolean',
                'information_security.pci' => 'required | boolean',
                // Tags
                'tags.*.field' => 'required | string | max: 255',
                'tags.*.value' => 'required | string | max: 255'
            ]);

            $request['app_type'] = 'itapps';
            $request['app_number'] = $this->makeKey('itapps');
            $itapp = $this->storeApp($request);
            $tags = Array();
            foreach ($request['tags'] as $key => $value) {
                $value['foreign_id'] = $itapp['id'];
                array_push($tags, $value);
            }

            $this->updateTags($tags);
            $gInfo = $this->storeGeneralInfo($request['general_ifo'], $itapp['id']);
            $sInfo = $this->storeSecurity($request['information_security'], $itapp['id']);
            $iInfo = $this->storeImage($request['image'], $itapp['id']);
        }catch(QueryException $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'record' => array(
                'description'=> $itapp->description,
                'app_number' => $itapp->app_number,
                'app_type' => $itapp->app_type,
                'author' => $itapp->author,
                'title' => $itapp->title,
                'id' => $itapp->id,
                'information_security' => $sInfo,
                'general_info' => $gInfo,
                'image_info' => $sInfo
            )
        ], 201);
    }

    public function postDynamicApp(Request $request){

        $this->validate($request, [
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'layout_type' => 'required|string|max:255',
            'prefix'      => 'required|string|max:5|min:1|unique:m6_apps',
        ]);

        try{
            $request['prefix'] = strtoupper($request['prefix']);
            $request['app_type'] = 'dynamic_app';
            $request['app_number'] = $this->makeKey('dynamic_app');

            $dynamicApp = $this->storeApp($request);
            $this->storeImage($request['image'], $dynamicApp['id']);
            AppsSettings::create([
              'field' => 'wo_request_type',
              'value' => $request['title'],
              'app_type' => 'dynamic_app_' . $dynamicApp['id']
            ]);

            $full_width =strtoupper($request["layout_type"]) != 'PROFILE';
            $newTab = $this->storeAppTab($dynamicApp['id'], $full_width );

            $newPanel = new \App\AppPanels;
            $newPanel->tab_id = $newTab['id'];
            $newPanel->column = 0;
            $newPanel->weight = 0;
            $newPanel->title = 'Information';
            $newPanel->description = 'description';
            $newPanel->save();

            return $dynamicApp;
        }catch(\Exception $e){} {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    protected function storeAppTab($appID, $full_width ){
        try{
            $newTab = array(
              "title"    => 'Home',
              "weight"   => 0,
              "app_id"   => $appID,
              "order"    => date('u'),
              "readOnly" => true,
              "full_width" => $full_width
            );

            $appTab = AppTabs::create($newTab);
            return $appTab;
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function storeAppPannel($item, $tabID){
        try{
            $item['tab_id'] = $appID;
            $appTab = AppTabs::create($item);
        }catch( \Exception $e){
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }

    protected function storeGeneralInfo($description, $id){
        try {
            $allValues = $description;
            $allValues['app_id'] = $id;

            $appInfoGeneral = AppInfoGeneral::create($allValues);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $appInfoGeneral;
    }

    protected function storeSecurity($security, $id){
        try {
            $allValues = $security;
            $allValues['app_id'] = $id;

            $informationSecurity = InformationSecurity::create($allValues);
        }catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $informationSecurity;
    }
    protected function storeImage($image, $id){
        try {
            $allValues = $image;
            $allValues['app_id'] = $id;

            $image = Image::create($allValues);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $image;
    }
    /**
     * Update the specified record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAllInfo(Request $request, $id) {
        try{
            $this->validate($request, [
                // ItApp
                'title' => 'string|max:255',
                'description' => 'string',
                // Description
                'general_info.server_hosting_model_settings_id'  => 'integer',
                'general_info.first_contact_group_settings_id' => 'integer',
                'general_info.app_management_settings_id' => 'integer',
                'general_info.sub_category_settings_id' => 'integer',
                'general_info.category_settings_id' => 'integer',
                'general_info.status_settings_id' => 'integer',
                'general_info.type_settings_id' => 'integer',
                'general_info.capabilities' => 'integer',
                'general_info.vendor_id' => 'string|max:255',
                'general_info.version' => 'string|max:255',
                // Information Security
                'facing' => 'boolean',
                'ssn' => 'integer',
                'phi' => 'boolean',
                'pci' => 'boolean',
                // Tags
                'formerly_known*.foreign_id' => 'integer',
                'also_known*.foreign_id' => 'integer',
                'formerly_known*.field' => 'string | max: 255',
                'also_known*.field' => 'string | max: 255',
                'formerly_known*.value' => 'string | max: 255',
                'also_known*.value' => 'string | max: 255'
            ]);

            $this->updateITApp($request, $id);
            $this->updateDescription($request['general_info']);
            $this->updateInformationSecurity($request['information_security']);
            $this->updateImage($request['image_info']);
            $resTag = $this->updateTags(array_merge($request['formerly_known'], $request['also_known']));
            $this->deleteTags($request['tags'], array_merge($request['formerly_known'], $request['also_known']));

        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The record has been updated', 'known_as' => $resTag ], 200);
    }

    protected function updateImage($imageData) {
        try {
            $image = Image::findOrFail($imageData['id']);
            $image->update($imageData);
        }catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function updateDescription($data) {
        try{
            unset($data['app_id']);
            $appInfoGeneral = AppInfoGeneral::findOrFail($data['id']);
            $appInfoGeneral->update($data);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function updateInformationSecurity($data) {
        try{
            unset($data['app_id']);
            $infoSecurity = InformationSecurity::findOrFail($data['id']);
            $infoSecurity->update($data);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function updateTags($tags) {
        try{
            $fka = Array();
            $aka = Array();
            foreach ($tags as $val) {
                if(!isset($val['id'])){
                    $val['id']= null;
                }
                $tag = TagsModel::updateOrCreate([
                    'id' => $val['id']
                ],$val);
                $tag['field'] === 'also_know_as' ? array_push($aka, $tag) : array_push($fka, $tag);
            }
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return [
            'also_know_as' => $aka,
            'formerly_known_as' => $fka
        ];
    }

    protected function deleteTags($before, $current) {
        foreach ($current as $cur) {
            $pos = array_search($cur, $before);
            if($pos>=0){
                unset($before[$pos]);
            }
        }foreach($before as $bef) {
            TagsModel::where('id', $bef['id'])->delete();
        }
        return $before;
    }


    /**
     * Remove the specified record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $m6App = M6Apps::findOrFail($id);
            $m6App->delete();

            return response()->json([ 'message' => 'The record has been deleted' ], 200);
        } catch(QueryException $e) {
            return response()->json([ 'error' => 'The record was not found' ], 404);
        }
    }

    protected function getFieldValue($appFieldId, $appRecordId, $type){
      try{
          switch ($type) {
              case 'text':
                  $result = FieldString::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'number':
                  $result = FieldNumber::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'timestamp':
                  $result = FieldDate::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'attachment':
                  $result = FieldAttachment::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'people':
                  $result = FieldAppSettings::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'autocomplete':
                  $result = FieldTag::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'boolean':
                  $result = FieldBoolean::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'trilean':
                  $result = FieldTrilean::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'referenced':
                  $result = FieldBoolean::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              case 'referencedToApp':
                  $result = FieldBoolean::where('record_id', $appRecordId)->where('field_id', $appFieldId)->first();
                  break;
              default:
                  throw new \Exception("Type undefined.", 400);
          }
        return $result;
      }catch(\Exception $e){
          throw new \Exception($e->getMessage(), $e->getCode());
      }
  }
}
