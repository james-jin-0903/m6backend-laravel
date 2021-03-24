<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\TagsModel;

class TagsController extends Controller {
    /**
     * Display a listing of the tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try{
            $tags = TagsModel::all();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($tags, 200);
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTag(Request $request) {
        try {
            $this->validate($request, [
                'foreign_id' => 'required | integer',
                'field' => 'required | string | max: 255',
                'value' => 'required | string | max: 255'
            ]);
            $tag = TagsModel::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'tag_id' => $tag->id
        ], 201);
    }

    /**
     * Store some tags in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSomeTags(Request $request) {
        try {
            $this->validate($request, [
                'params.*.foreign_id' => 'required | integer',
                'params.*.field' => 'required | string | max: 255',
                'params.*.value' => 'required | string | max: 255'
            ]);
            TagsModel::insert($request['params']);
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful'
        ], 201);
    }
    /**
     * Display the specified group of tags or tag.
     *
     * @param  $column
     * @param  $value
     * @return \Illuminate\Http\Response
     */
    public function showTagsByConsult($column, $value) {
        try{
            $response = TagsModel::where($column,$value)->get()->groupBy('field');
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }
    /**
     * Update the specified tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTag(Request $request, $id) {
        try{
            $this->validate($request, [
                'foreign_id' => 'integer',
                'field' => 'string | max: 255',
                'value' => 'string | max: 255'
            ]);
            $tag = TagsModel::findOrFail($id);
            $tag->update($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The tag has been updated' ], 200);
    }

    /**
     * Update some tags at the same time in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSomeTags(Request $request) {
        try{
            $this->validate($request, [
                'params.*.foreign_id' => 'integer',
                'params.*.field' => 'string | max: 255',
                'params.*.value' => 'string | max: 255'
            ]);
            foreach ($request['params'] as $val) {
                if(!isset($val['id'])){
                    $val['id']= null;
                }
                TagsModel::updateOrCreate([
                    'id' => $val['id']
                ],$val);
            }
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The tag has been updated' ]);
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $tag = TagsModel::findOrFail($id);
            $tag->delete();

            return response()->json(['message' => "The tag has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The tag was not found" ], 404);
        }
    }
}
