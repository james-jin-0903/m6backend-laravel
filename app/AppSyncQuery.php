<?php

namespace App;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;


class AppSyncQuery {

    protected $headers = [
        'Content-Type' => 'application/json'
    ];
    protected $json = [];
    protected $client;
    protected $endpoint = "";

    function __construct($query, $variables, $insideInput = true, $nextToken = ""){
        $this->endpoint = getenv('COREDB_URL');
        $this->headers["x-api-key"] = getenv('COREDB_API');
        $this->json["query"] = $query;

        switch (true) {
            case !empty($variables) && $insideInput:
                $this->json["variables"]["input"] = $variables;
                break;

            case !empty($variables) && !$insideInput:
                $this->json["variables"] = $variables;
                break;
        }
        if($nextToken) $this->json["variables"]["nextToken"] = $nextToken;

        $this->client = new Client();
    }

    function sendRequest() {
        try{
            return $this->processResponse($this->client->post($this->endpoint, [
                'headers' => $this->headers,
                'json' => $this->json
            ]));
        } catch(ClientException $e) {
            return $e;
        }
    }

    private function processResponse($response) {
        return json_decode($response->getBody(), true);
    }
}
