<?php

namespace App;
use GetStream\StreamChat\Client;

Class GSChatController {

    /**
     * @var Client
     */
    public $client;

    function __construct() {
       $this->client = new Client(env('GS_KEY', ''), env('GS_SECRET', ''));
    }
}
