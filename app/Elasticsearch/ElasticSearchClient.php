<?php

namespace App\Elasticsearch;

use Illuminate\Database\Eloquent\Model;
use Elasticsearch\ClientBuilder;

class ElasticSearchClient extends Model {
    public $client;

    public function __construct()
    {
        $hosts = [
            [
                'host' => getenv('ELASTICSEARCH_URL'),
                'scheme' => 'https',
                // 'path' => '/',
                'port' => 443,
                'user' => getenv('ELASTICSEARCH_USERNAME'),
                'pass' => getenv('ELASTICSEARCH_PASSWORD')
            ],
        ];
        
        $this->client = ClientBuilder::create() // Instantiate a new ClientBuilder
            ->setHosts($hosts) // Set the hosts
            ->build();    
    }

    public static function processHits($json) 
    {
        if( isset($json["hits"]["hits"]) ) {
            $res = [];
            
            for( $x = 0; $x< count($json["hits"]["hits"]); $x++ ) {
                array_push($res, $json["hits"]["hits"][$x]["_source"]);
            }

            return $res;
        }
        
        return [];
    }

}