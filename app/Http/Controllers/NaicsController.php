<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\AppSync\NaicsCodesDynamo;
use Exception;

class NaicsController extends Controller {
    public function bulkUpload(Request $request) {
        try{
            [ 'naics' => $naics ] =  $request->only('naics');
             
            $naicsCodesDynamo = new NaicsCodesDynamo();

            foreach ($naics as $naic) {
                $client = $naicsCodesDynamo->create($naic);
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

            $naicsCodesDynamo = new NaicsCodesDynamo();
            $client = $naicsCodesDynamo->all($nextToken);
            $json = $client->sendRequest();
            return $json;
        } catch(Exception $e) {
            return $e;
        }
    }

}