<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Elasticsearch\ElasticSearchClient;

class RegionsElasticController extends Controller {

    //gets all
    public function index(Request $request) {

        $parents = $request->input('parents') ? [ $request->input('parents') ] : ["0"];

        try{
            $elastic = new ElasticSearchClient();
            $params = [
                'index' => 'regions',
                "size"  => 100,
                'body'  => [
                    'query' => [
                        'terms' => [
                            'parents' => $parents
                        ]
                    ]
                ]
            ];
            
            return ElasticSearchClient::processHits($elastic->client->search( $params ));
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCodesByIds(Request $request) {
        $this->validate($request, [
            'ids' => 'required'
        ]);

        $ids = $request->input('ids');

        try {
            $elastic = new ElasticSearchClient();
            $params = [
                'index' => 'regions',
                "size"  => 100,
                'body'  => [
                    "query" => [
                        "terms" => [
                            "parents" => $ids
                        ]
                    ]
                ]
            ];

            $arr = ElasticSearchClient::processHits($elastic->client->search( $params ));
            $json = [];

            for( $x = 0; $x < count($ids); $x++ ) {
                $json[$ids[$x]] = [];
            }

            for( $x = 0; $x < count($arr); $x++ ) {
                array_push($json[ $arr[$x]["parents"]["0"] ], $arr[$x]);
            }

            return $json;
        } catch(\Exception $e) {
            return $e->getMessage();
        }

    }
}