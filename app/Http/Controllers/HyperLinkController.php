<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\ContactNotification;

class HyperLinkController extends Controller {

  public function getUrl(Request $request) {
    try{
      $res = get_meta_tags($request->url, false);

      return response()->json($res);
    }catch(\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }

  }
}
