<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Image;

class ImageController extends Controller {

    /**
     * Store a newly created record image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImage(Request $request) {
        try {
            $this->validate($request, [
                'app_id' => 'required | integer',
                'image_url' => 'string | max: 255',
            ]);

            $image = Image::create($request->all());
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([
            'message' => 'Successful',
            'image_id' => $image->id
        ], 201);
    }

    /**
     * Display the specified record image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByAppID($appID) {
        try {
            $response = Image::where('app_id',$appID)->first();
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($response, 200);
    }
    /**
     * Update the specified record image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateImage(Request $request, $id) {
        try{
            $this->validate($request, [
                'image_url' => 'required | string | max: 255',
            ]);
            $image = Image::findOrFail($id);
            $image->update($request->except(['app_id']));
        } catch(QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json([ 'message' => 'The record image has been updated' ], 200);
    }

    /**
     * Remove the specified record image from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $image = Image::findOrFail($id);
            $image->delete();

            return response()->json(['message' => "The record image has been deleted" ], 200);
        } catch(QueryException $e) {
            return response()->json(['error' => "The record image was not found" ], 404);
        }
    }
}
