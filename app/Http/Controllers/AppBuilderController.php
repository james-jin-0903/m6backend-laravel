<?php

namespace App\Http\Controllers;

use App\AppPanels;
use App\AppFields;
use App\AppRecords;
use App\AppFieldValues;
use App\AppTabs;
use App\M6Apps;
use App\AppFieldValueHelperMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\RecordsCount;
use Carbon\Carbon;
use App\Http\Traits\RecordTrait;

class AppBuilderController extends Controller
{
  use RecordTrait;

  public function getAllApps()
  {
    return M6Apps::where('app_type', 'dynamic_app')->get();
  }

  public function getApp($app = 1)
  {
    $app = M6Apps::where('id', $app)->with(['tabs.panels.fields', 'tabs.panels.tables', 'tabs.panels.tables.fields', 'fields'])->first();
    $app['helperMedia'] = AppFieldValueHelperMedia::where('app_id', $app->id)->get();
    return $app;
  }

  public function updateApp(Request $request)
  {
    $this->validate($request, [
      'params' => 'required',
      'params.prefix' => 'required|string|max:5|min:1'
    ]);

    $params = request('params');




    $m6Apps = M6Apps::find($params["id"]);
    if ($m6Apps->prefix !== $params["prefix"]) {
      $this->validate($request, [
        'params.prefix' => 'unique:m6_apps'
      ]);
    }
    $m6Apps->update($params);

    return ['update' => true];
  }

  public function deleteApp($app)
  {
    try {
      $m6app = M6Apps::find($app);
      $records = $m6app->records();
      $m6app->delete();
      $records->delete();

      return ['success' => true];
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function listTabs(Request $request)
  {
    return AppTabs::where('app_id', '==', $request->appId)->get();
  }

  public function listPanels(Request $request)
  {
    return AppPanels::where('tab_id', '==', $request->appId)->get();
  }

  public function listFields(Request $request)
  {
    return AppFields::where('panel_id', '==', $request->panelId)->get();
  }

  public function listAllFields(Request $request)
  {
    $tabs = AppTabs::where('app_id', $request->appId)
      ->with(['panels', 'panels.fields', 'panels.fields.AppRoles' => function ($query) use ($request) {
        $query->where('role_id', $request->role_id);
      }])->get();
    $fields = [];
    // Get all fields inside panels
    // TODO: Refactor this.
    if ($request->nested) {
      // Get all fields that are not inside a panel
      $appFields = AppFields::where('app_id', $request->appId)->where('panel_id', null)->get();
      $fields = array_merge($tabs->toArray(), $appFields->toArray());
      return $fields;
    }
    foreach ($tabs as $tab) {
      foreach ($tab['panels'] as $panel) {
        foreach ($panel['fields'] as $field) {
          $fields[] = $field;
        }
      }
    }

    // Get all fields that are not inside a panel
    $appFields = AppFields::where('app_id', $request->appId)->where('panel_id', null)
      ->with(['AppRoles' => function ($query) use ($request) {
        $query->where('role_id', $request->role_id);
      }])->get();
    foreach ($appFields as $appField) {
      $fields[] = $appField;
    }
    return $fields;
  }

  public function listRecords(Request $request)
  {
    return AppRecords::where('app_id', '==', $request->appId)->get();
  }

  public function listFieldsValues(Request $request)
  {
    return AppFieldValues::where('record_id', '==', $request->recordId)->get();
  }

  public function storeTab(Request $request)
  {
    try {
      $this->validate($request, [
        'appID' => 'required',
        'weight' => 'required',
        'title' => 'required',
        'fullWidth' => 'required'
      ]);

      $newTab = new AppTabs;
      $newTab->order = date('u');
      $newTab->app_id = $request->appID;
      $newTab->weight = $request->weight;
      $newTab->title = $request->title;
      $newTab->full_width = $request->fullWidth;
      $newTab->save();

      // Add empty panels array
      $newTab->panels = array();
      return $newTab;

    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function storePanel(Request $request)
  {
    try {
      $this->validate($request, [
        'tabID' => 'required',
        'column' => 'required',
        'weight' => 'required',
        'title' => 'required',
        'title_pos' => 'required'
      ]);

      $newPanel = new AppPanels;
      $newPanel->tab_id = $request->tabID;
      $newPanel->column = $request->column;
      $newPanel->weight = $request->weight;
      $newPanel->title = $request->title;
      $newPanel->description = $request->description;
      $newPanel->title_pos = $request->title_pos;
      $newPanel->save();

      // Add empty fields array
      $newPanel->fields = array();

      return $newPanel;

    } catch (\Exception $e) {
      return $e;
    }
  }

  public function storeField(Request $request)
  {
    try {
      $validationArray = [
        'app_id' => 'integer',
        'panel_id' => 'integer',
        'label' => 'required | string',
        'type' => 'required | string',
        'machine_name' => 'regex:/^[a-zA-Z0-9_]*$/'
      ];

      if ($request->type === 'referenced') {
        $validationArray['referenced_field'] = 'required | integer';
      } else if ($request->type === 'referencedToApp') {
        $validateArray['referenced_app'] = 'required | integer';
      }
      $this->validate($request, $validationArray);

      $allValues = $request->all();
      // Sanitization for non referenced fields
      if ($allValues['type'] !== 'referenced') {
        unset($allValues['referenced_field']);
      }
      if ($allValues['type'] !== 'referencedToApp') {
        unset($allValues['referenced_app']);
      }
      $allValues['metadata'] = json_encode($request->metadata);
      $allValues['show_in_table'] = false;
      $allValues['table_index'] = 0;

      if ($request->type === "helper-media") {
        $helperMedia["helper_media"] = $request->helperMediaURL;
        $helperMedia["app_id"] = $request->app_id;
        AppFieldValueHelperMedia::create($helperMedia);
      }
      return AppFields::create($allValues);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function storeFields(Request $request)
  {
    try {
      $validationArray = [
        'app_id' => 'integer',
        'panel_id' => 'integer',
        'type' => 'required | string',
        'machine_name' => 'regex:/^[a-zA-Z0-9_]*$/'
      ];
      $result = array();
      $this->validate($request, $validationArray);

      $allValues = $request->all();
      // Sanitization for non referenced fields
      unset($allValues['referenced_field']);
      unset($allValues['referenced_app']);
      $allValues['metadata'] = json_encode($request->metadata);
      $allValues['show_in_table'] = false;
      $allValues['table_index'] = 0;
      foreach ($allValues['labels'] as $label) {
        $allValues['label'] = $label;
        array_push($result, AppFields::create($allValues));
      }
      return ['result' => $result];
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  private function getLatestRecordNumber($appId, $prefix)
  {

    $res = RecordsCount::where([['app_id', '=', $appId], ['year', '=', Carbon::now()->year]])->get();
    $count = !count($res) ? str_pad(1, 8, '0', STR_PAD_LEFT) : str_pad($res[0]->count + 1, 8, '0', STR_PAD_LEFT);

    return $prefix . '#' . $count . '-' . Carbon::now()->year;
  }

  public function updateOrCreateRecordCount($appId, $year)
  {

    $res = RecordsCount::where([['app_id', '=', $appId], ['year', '=', $year]])->get();

    try {
      if (!count($res)) {
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
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function storeRecord(Request $request)
  {
    try {
      $this->validate($request, [
        'record' => 'required',
        'prefix' => 'required'
      ]);

      ['record' => $record, 'prefix' => $prefix] = $request->only(['record', 'prefix']);

      $record_number = $this->getLatestRecordNumber($record['appID'], $prefix);

      $newAppRecord = new AppRecords;
      $newAppRecord->app_id = $record['appID'];
      $newAppRecord->record_number = $record_number;
      $newAppRecord->title = $record['title'];
      $newAppRecord->description = $record['description'];
      $newAppRecord->status = $record['status'];
      $newAppRecord->author = $record['author'];
      $newAppRecord->image = $record['image'];
      $newAppRecord->metadata = json_encode($record['metadata']);
      $newAppRecord->save();

      $this->updateOrCreateRecordCount($record['appID'], Carbon::now()->year);

      return $newAppRecord;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function storeFieldValue(Request $request)
  {
    try {

      $newFieldValue = new AppFieldValues;
      $newFieldValue->record_id = $request->record_id;
      $newFieldValue->field_id = $request->field_id;
      $newFieldValue->value = $request->value;
      $newFieldValue->save();

      return $newFieldValue;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function updatePanel(Request $request)
  {
    try {
      $this->validate($request, [
        'column' => 'required',
        'weight' => 'required',
        'title' => 'required',
        'title_pos' => 'required'
      ]);

      $panel = AppPanels::find(request('id'));

      $panel->update($request->only(['column', 'weight', 'title', 'description', 'title_pos']));

      return ['success' => true];

    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function movePanel(Request $request, $panelId)
  {
    $this->validate(
      $request,
      ['newWeight' => 'integer | required'],
      ['newColumn' => 'integer | required']
    );

    $newWeight = $request->newWeight;
    $newColumn = $request->newColumn;

    try {
      $panel = AppPanels::findOrFail($panelId);
      if ($panel->column !== $newColumn) { // Move between columns
        $newColumnPanelsCount =
          AppPanels::where('tab_id', $panel->tab_id)
            ->where('column', $newColumn)
            ->count();

        // Validate new weight
        if ($newWeight > $newColumnPanelsCount) {
          return response()->json(['message' => 'Invalid Request'], 400);
        }

        AppPanels::where('tab_id', $panel->tab_id)
          ->where('column', $newColumn)
          ->where('weight', '>=', $newWeight)
          ->update(['weight' => DB::raw('weight + 1')]);

        AppPanels::where('tab_id', $panel->tab_id)
          ->where('column', $panel->column)
          ->where('weight', '>', $panel->weight)
          ->update(['weight' => DB::raw('weight - 1')]);

        $panel->column = $newColumn;
        $panel->weight = $newWeight;
        $panel->save();
      } else { // Move inside column
        if ($newWeight === $panel->weight) {
          return response()->json(['message' => 'Invalid Request'], 400);
        }

        $newColumnPanelsCount =
          AppPanels::where('tab_id', $panel->tab_id)
            ->where('column', $newColumn)
            ->count();

        // Validate new weight
        if ($newWeight > $newColumnPanelsCount) {
          return response()->json(['message' => 'Invalid Request'], 400);
        }

        // Find in between items to move them appropriately
        // to take old space and make new space for moving item
        $min = min($newWeight, $panel->weight);
        $max = max($newWeight, $panel->weight);

        $inBetweenItems = collect(range($min, $max))
          ->reject(function ($value, $key) use ($panel) {
            return $value === $panel->weight;
          });

        if ($newWeight > $panel->weight) { // Moved forward
          $moveOp = '-';
        } else if ($newWeight < $panel->weight) { // Moved backward
          $moveOp = '+';
        }

        AppPanels::where('tab_id', $panel->tab_id)
          ->where('column', $panel->column)
          ->whereIn('weight', $inBetweenItems)
          ->update([
            'weight' => DB::raw("weight ${moveOp} 1")
          ]);

        $panel->weight = $newWeight;
        $panel->save();
      }

      return response()->json(['success' => true], 200);
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function updateField(Request $request)
  {
    try {
      $validateArray = [
        'label' => 'required',
        'type' => 'required',
      ];
      $isReferencedField = $request->type;
      if ($isReferencedField === 'referenced') {
        $validateArray['referenced_field'] = 'required | integer';
      } else if ($isReferencedField === 'referencedToApp') {
        $validateArray['referenced_app'] = 'required | integer';
      }
      $this->validate($request, $validateArray);
      $field = AppFields::find($request->id);
      $field->weight = $request->weight;
      $field->label = $request->label;
      $field->type = $request->type;
      $field->metadata = json_encode($request->metadata);
      if ($isReferencedField === 'referenced') {
        $field->referenced_field = $request->referenced_field;
      } else if ($isReferencedField === 'referencedToApp') {
        $field->referenced_app = $request->referenced_app;
      } else {
        $field->referenced_field = null;
        $field->referenced_app = null;
      }
      $field->update();

      if ($request->type === "helper-media") {
        AppFieldValueHelperMedia::where("app_id", $request->app_id)->update([
          'helper_media' => $request->helperMediaURL
        ]);
      }
      return $field;

    } catch (\Exception $e) {
      return $e;
    }
  }

  public function moveField(Request $request, $fieldId)
  {
    $this->validate(
      $request,
      ['newWeight' => 'integer | required']
    );

    $newWeight = $request->newWeight;

    try {
      $field = AppFields::findOrFail($fieldId);
      $count = AppFields::where('panel_id', $field->panel_id)->count();

      if ($newWeight >= $count || $newWeight === $field->weight) {
        return response()->json(['message' => 'Invalid newWeight'], 400);
      }

      // Find in between items to move them appropriately
      // to take old space and make new space for moving item
      $min = min($newWeight, $field->weight);
      $max = max($newWeight, $field->weight);

      $inBetweenItems = collect(range($min, $max))
        ->reject(function ($value, $key) use ($field) {
          return $value === $field->weight;
        });

      if ($newWeight > $field->weight) { // Moved forward
        $moveOp = '-';
      } else if ($newWeight < $field->weight) { // Moved backward
        $moveOp = '+';
      }

      AppFields::whereIn('weight', $inBetweenItems)
        ->where('panel_id', $field->panel_id)
        ->update([
          'weight' => DB::raw("weight ${moveOp} 1")
        ]);
      $field->weight = $newWeight;
      $field->save();

      return response()->json(['success' => true], 200);
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function GetTableFields(Request $request)
  {
    try {
      return AppFields::where('app_id', $request->appId)->where('show_in_table', true)
        ->orderBy('table_index', 'ASC')
        ->get();
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function UpdateTableFields(Request $request)
  {
    try {
      if (is_array($request->tableItems)) {
        AppFields::where('app_id', $request->appId)->update(['show_in_table' => false]);

        foreach ($request->tableItems as $item) {
          $field = AppFields::find($item['id']);
          $field->show_in_table = $item['show_in_table'];
          $field->table_index = $item['table_index'];
          $field->update();
        }
        return response()->json(['success' => true], 200);
      } else {
        return response()->json(['success' => false], 422);
      }
    } catch (\Exception $e) {
      return $e;
    }
  }


  public function updateRecord(Request $request)
  {
    try {
      $record = AppRecords::find($request->id);

      $record->title = $request->title;
      $record->description = $request->description;
      $record->status = $request->status;
      $record->author = $request->author;
      $record->image = $request->image;
      $record->class = $request->class;
      $record->category = $request->category;
      $record->type = $request->type;
      $record->state = $request->state;
      $record->metadata = json_encode($request->metadata);
      $record->update();

      return ['success' => true];
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function deleteRecord($record)
  {
    try {
      $recordItem = AppRecords::find($record);
      $recordItem->delete();
      \App\AppFieldValueAttachment::where(['record_id' => $record])->delete();
      \App\AppFieldValueBoolean::where(['record_id' => $record])->delete();
      \App\AppFieldValueTrilean::where(['record_id' => $record])->delete();
      \App\AppFieldValueDate::where(['record_id' => $record])->delete();
      \App\AppFieldValueNumber::where(['record_id' => $record])->delete();
      \App\AppFieldValueAppSettings::where(['record_id' => $record])->delete();
      \App\AppFieldValueString::where(['record_id' => $record])->delete();

      return ['success' => true];
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function updateFieldValue(Request $request, AppFieldValues $fieldValue)
  {
    try {
      $fieldValue->value = $request->value;
      $fieldValue->update();

      return $fieldValue;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function deleteTab(Request $request)
  {
    try {
      $tab = AppTabs::find($request->tab);
      if ($tab) {
        $tab->delete();
      }

      return $tab;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function copyPanel(Request $request, $id)
  {
    try {
      $panel = AppPanels::where('id', $id)->with('fields')->firstOrFail();

      $newPanel = $panel->replicate();

      // Change tab if sent in request
      if ($request->tabId) {
        $newPanel->tab_id = $request->tabId;
      }

      // Change column if send in request
      if ($request->column) {
        $newPanel->column = $request->column;
      }

      // Save new panel
      $newPanel->save();

      // Clone all fields
      $newFields = [];
      foreach ($panel->fields as $field) {
        $newFields[] = $field->replicate()
          ->fill([
            'panel_id' => $newPanel->id,
            'machine_name' => null
          ])
          ->save();
      }

      $newPanel->fields = $newFields;

      return $newPanel;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function deletePanel(Request $request)
  {
    try {
      $panel = AppPanels::find($request->panel);

      AppPanels::where('tab_id', $panel->tab_id)
        ->where('column', $panel->column)
        ->where('weight', '>', $panel->weight)
        ->update(['weight' => DB::raw('weight - 1')]);

      $panel->delete();
      return $panel;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function deleteField(Request $request)
  {
    try {
      $field = AppFields::find($request->field);
      if (isset($field["machine_name"])) $field["machine_name"] = $field["machine_name"] . '$' . date('Y-m-d H:i:s');
      $field->update();

      AppFields::where('panel_id', $field->panel_id)
        ->where('weight', '>', $field->weight)
        ->update(['weight' => DB::raw('weight - 1')]);

      $field->delete();
      return $field;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function updateTab(Request $request, $tab)
  {
    try {
      $this->validate($request, [
        'tabToEdit' => 'required'
      ]);

      $currentTab = AppTabs::find($tab);

      return $currentTab->update(request('tabToEdit'));
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function switchOrderTabs(Request $request)
  {
    try {

      $this->validate($request, [
        'idsAndOrders' => 'required',
      ]);

      $idsAndOrders = request('idsAndOrders');

      foreach ($idsAndOrders as $item) {
        $appTab = AppTabs::find($item["id"]);
        $appTab->update(['order' => $item["order"]]);
      }

      return ['success' => true];
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function AddOrderToExistingTabs()
  {
    $tabs = AppTabs::all();

    $res = [];
    foreach ($tabs as $tab) {
      $milliseconds = round(microtime(true) * 1000);

      $tabItem = AppTabs::find($tab->id);
      $r = $tabItem->update(['order' => $milliseconds]);

      array_push($res, $r);
    }

    return $res;
  }

  public function getRecordById($id)
  {
    return AppRecords::find($id);
  }

  public function updateListOfFields(Request $request)
  {
    try {
      $this->validate($request, [
        'fields' => 'required'
      ]);

      foreach ($request->fields as $key => $field) {
        unset($field["created_at"]);
        unset($field["updated_at"]);
        AppFields::where('id', $field['id'])->update($field);
      }

      return response()->json(['message' => 'successfull'], 200);

    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 400);
    }
  }

  public function getFieldsByApp($app_id)
  {
    try {
      $fields = AppFields::where('app_id', $app_id)->get();
      return response()->json($fields, 200);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 400);
    }
  }

}
