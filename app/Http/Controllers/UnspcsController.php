<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\AppSync\UnspcCodesDynamo;
use Exception;

class UnspcsController extends Controller {
    public function bulkUpload(Request $request) {
        try{
            [ 'unspcs' => $unspcs ] = $request->only('unspcs');

            $unspcCodesDynamo = new UnspcCodesDynamo();

            foreach ($unspcs as $unspc) {
                $client = $unspcCodesDynamo->create($unspc);
                $client->sendRequest();
            }

            return ['done' => true];
        } catch(Exception $e) {
            return $e;
        }
    }

    public function index(Request $request) {
        try {
            [ 'nextToken' => $nextToken ] = $request->only('nextToken');
            $unspcCodesDynamo = new UnspcCodesDynamo();
            $client = $unspcCodesDynamo->all($nextToken);
            $json = $client->sendRequest();
            return $json;
        } catch(Exception $e) {
            return $e;
        }
    }

}