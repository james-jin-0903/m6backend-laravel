<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\RationalizationAttributes;
use App\TagsModel;

class RationalizationAttributesController extends Controller {

    /**
     * Display the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByAppID($appID) {
        try {
            $response = RationalizationAttributes::where('app_id',$appID)->with([
                'capability', 'rationalizationKind', 'applicationValue'
            ])->first();

        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
    public function updateRationalizationAttribute(Request $request, $id) {
        try{
            $this->validate($request, [
                'ratio_of_cost_to_user' => 'numeric | max: 999999999999999999',
                'rationalization_kind' => 'integer  | nullable',
                'total_annual_cost' => 'numeric | max: 999999999999999999',
                'estimated_users' => 'integer | digits_between: 1, 11',
                'retirement_date' => 'date',
                'capabilities' => 'integer  | nullable',
                'if_no_need' => 'string | nullable',
                'is_needs' => 'boolean',
                'app_id' => 'required | integer'
            ]);
            RationalizationAttributes::updateOrCreate([
                'app_id' => $request->app_id
            ],$request->except(['id']));

            $this -> updateTags($request['application_value'], $request['app_id']);
            $this -> deleteTags($request['first_state'], $request['application_value']);
            return response()->json($request->all(), 200);

        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The record has been updated' ], 200);
    }

    protected function updateTags($groupTags, $id) {
        try{
            $tag = array(
                'field' => 'ratio_attributes',
                'foreign_id' => $id
            );
            foreach ($groupTags as $newTag) {
                $tag['value'] = $newTag;
                TagsModel::firstOrCreate([
                    'foreign_id' => $tag['foreign_id'],
                    'field' => $tag['field'],
                    'value' => $tag['value']
                ],$tag);
            }
        }catch( QueryException $e ) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
}
