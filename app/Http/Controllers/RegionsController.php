<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\AppSync\RegionDynamo;
use Exception;

class RegionsController extends Controller {
    public function bulkUpload(Request $request) {
        try{
            $regions = $request->only('regions');

            $regionDynamo = new RegionDynamo();

            foreach ($regions["regions"] as $region) {
                $client = $regionDynamo->create($region);
                $client->sendRequest();
            }

            return ['done' => true];
        } catch(Exception $e) {
            return $e;
        }
    }

    public function index() {
        try {
            $regionDynamo = new RegionDynamo();
            $client = $regionDynamo->all();
            $json = $client->sendRequest();
            return $json;
        } catch(Exception $e) {
            return $e;
        }
    }

}