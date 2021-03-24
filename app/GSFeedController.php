<?php

namespace App;
use GetStream\Stream\Client;

Class GSFeedController {

    /**
     * @var Client
     */
    public $client;

    function __construct() {
       $this->client = new Client(env('GS_KEY', ''), env('GS_SECRET', ''));
    }
}
