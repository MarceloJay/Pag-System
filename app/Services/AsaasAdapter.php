<?php

namespace App\Services;

use Config;
use GuzzleHttp\Client;

class AsaasAdapter
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Config::get('asaas.base_uri'),
            'headers' => [
                'accept' => 'application/json',
                'access_token' => Config::get('asaas.api_key'),
                'content-type' => 'application/json',
            ],
        ]);
    }

    public function sendRequest($requestType, $url, $body = [])
    {
        if($body != []){
            $options = [
                'body' => json_encode($body),
            ];
            $response = $this->client->request($requestType, $url, $options);
        }else{
            $response = $this->client->request($requestType, $url);
        }
        

        return $response->getBody();
    }
}
