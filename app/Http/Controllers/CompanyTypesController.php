<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\AppSync\CompanyTypesDynamo;
use Exception;

class CompanyTypesController extends Controller {
    
    public function bulkUpload(Request $request) {
        try{
            [ 'types' => $types ] = $request->only('types');

            $companyTypesDynamo = new CompanyTypesDynamo();

            foreach ($types as $type) {
                $client = $companyTypesDynamo->create($type);
                $client->sendRequest();
            }

            return ['done' => true];
        } catch(Exception $e) {
            return $e;
        }
    }

    public function index(Request $request) {
        [ 'nextToken' => $nextToken ] = $request->only('nextToken');
        try {
            $companyTypesDynamo = new CompanyTypesDynamo();
            $client = $companyTypesDynamo->all($nextToken);
            $json = $client->sendRequest();
            return $json;
        } catch(Exception $e) {
            return $e;
        }
    }

}