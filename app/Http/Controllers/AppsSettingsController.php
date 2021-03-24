<?php
namespace App\Http\Controllers;

use App\WorkActivityFields;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\AppsSettings;

class AppsSettingsController extends Controller {
  /**
   * Display a listing of the record.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index($appType) {
    try{
      $appSetting = AppsSettings::where('app_type', $appType)->get();
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($appSetting, 200);
  }

  /**
   * Display a listing of App Activities.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAppActivities($appId) {
    try{
      $appActivities = [];
      $appSetting = AppsSettings::where('app_type', 'dynamic_app_' . $appId)->first();
      if (!empty($appSetting)) {
        $appActivitiesResult = AppsSettings::where('parent_id', $appSetting['id'])->with('fields')->get();
        foreach ($appActivitiesResult as $activity) {
          $appActivity = [
            'id' => $activity['id'],
            'value' => $activity['value'],
            'parent_id' => $activity['parent_id'],
            'fields' => []
          ];
          foreach ($activity['fields'] as $field) {
            $appActivity['fields'][] = $field['field_id'];
          }
          $appActivities[] = $appActivity;
        }
      }
      return response()->json($appActivities, 200);
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Store a newly created record in storage.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function storeAppSettingItapp(Request $request) {
    try {
      $this->validate($request, [
        'field' => 'required|string|max:255',
        'value' => 'required|string|max:255'
      ]);

      $allValues = $request->all();
      $allValues['app_type'] = 'itapps';
      $appsSetting = AppsSettings::create($allValues);
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json([
      'message' => 'Successful',
      'app_setting_id' => $appsSetting->id
    ], 201);
  }

  /**
   * Store a newly created record in storage.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request)
  {
    try {
      $validation = [
        'field' => 'required|string|max:255',
        'value' => 'required|string|max:255',
      ];
      $allValues = $request->all();
      // TODO: Validate Work Activity Fields.
      if (str_contains('dynamic_app', $allValues['app_type'])) {
        $validation[] = [
          'app_id' => 'required|number',
          'parent_id' => 'number'
        ];
      }
      $this->validate($request, $validation);

      $parentSetting = AppsSettings::where('app_type', $allValues['app_type'])->first();
      if (!empty($parentSetting)) {
        $allValues['parent_id'] = $parentSetting['id'];
      }
      $allValues['app_type'] = 'm6works';

      $appsSetting = AppsSettings::create($allValues);

      if (isset($allValues['woFields'])) {
        foreach ($allValues['woFields'] as $fieldId) {
          if(!$fieldId) continue;
          WorkActivityFields::create([
            'setting_id' => $appsSetting['id'],
            'field_id' => $fieldId,
            'app_id' => $allValues['app_id']
          ]);
        }
      }

      return response()->json([
        'message' => 'Successful',
        'app_setting' => $appsSetting
      ], 201);
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Display the specified group of records or record.
   *
   * @param  $column
   * @param  $value
   * @return \Illuminate\Http\JsonResponse
   */
  public function showAppsSettingsByConsult($column, $value) {
    try{
      $response = AppsSettings::where($column,$value)->get();
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($response, 200);
  }

  /**
   * Display the specified group of records.
   *
   * @param $column
   * @param Request $values
   * @return \Illuminate\Http\JsonResponse
   */
  public function showAppsSettingsByParams($column, Request $values) {
    try{
      $response = AppsSettings::whereIn($column, $values['params'])->get()->groupBy('field');
      if (isset($values['nesting']) && $values['nesting']) {
        $nestedSettings = [];
        foreach ($response as $setting => $values) {
          foreach ($values as $value) {
            $newValue = [
              'id' => $value['id'],
              'field' => $value['field'],
              'value' => $value['value'],
              'app_type' => $value['app_type'],
              'parent_id' => $value['parent_id'],
            ];
            if (null == $value['parent_id']) {
              $nestedSettings[$setting][$value['id']] = $newValue;
            } else {
              $nestedSettings[$setting][$value['parent_id']]['children'][] = $newValue;
            }
          }
        }

        foreach ($nestedSettings as $key => $value) {
          $nestedSettings[$key] = array_values($nestedSettings[$key]);
        }
        return response()->json($nestedSettings, 200);
      }
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($response, 200);
  }

  /**
   * Display the specified record.
   *
   * @param  int  $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function showByID($id) {
    try {
      $response = AppsSettings::where('id',$id)->first();
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($response, 200);
  }
  /**
   * Update the specified record in storage.
   *
   * @param Request $request
   * @param  int  $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateAppSetting(Request $request, $id) {
    try{
      $this->validate($request, [
        'field' => 'string|max:255',
        'value' => 'string|max:255'
      ]);

      $appsSetting = AppsSettings::findOrFail($id);
      $appsSetting->update($request->except(['app_type', 'parent_id', 'app_id']));
    } catch(QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json([ 'message' => 'The record has been updated' ], 200);
  }

  /**
   * Remove the specified record from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id) {
    try{
      $AppSetting = AppsSettings::findOrFail($id);
      $AppSetting->delete();

      return response()->json(['message' => "The record has been deleted" ], 200);
    } catch(QueryException $e) {
      return response()->json(['error' => "The record was not found" ], 404);
    }
  }
}
