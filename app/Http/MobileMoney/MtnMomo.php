<?php
// app/Services/MtnMomo.php

namespace App\Http\MobileMoney;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class MtnMomo
{
    private $subscriptionKey;
    private $callbackUrl;
    private $domain;
    private $userId;
    private $accessTokenUrl = 'https://sandbox.momodeveloper.mtn.com/collection/token/';
    private $requestToPay = 'https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay';


    public function __construct($subscriptionKey, $callbackUrl, $domain)
    {
        $this->subscriptionKey = $subscriptionKey;
        $this->callbackUrl = $callbackUrl;
        $this->domain = $domain;
       
    }

    public function apiUser($url, $userId)
    {
        echo 'in apiUser '.$this->userId;

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'X-Reference-Id' => $userId,
            'Content-Type' => 'application/json'
        ])->post($url, [
            'providerCallbackHost' => $this->domain
        ]);

        // echo $response->status();
        return $response->status();
    }

    public function apiToken ($url, $userId){

        $urltoken = $url .'/'. $userId .'/apikey';

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'Content-Type' => 'application/json'
        ])->post($urltoken);

        return $response->body();
    }


    public function accessToken($userId, $apiKey){

        $response = Http::withBasicAuth($userId, $apiKey)
                        ->withHeaders([
                            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                            'Content-Type' => 'application/json'
                        ])->post($this->accessTokenUrl);
        return $response;
    }





    public function requestToPay($accessToken, $requestBody){

        $response = Http::withToken($accessToken)->withHeaders([

            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'Content-Type' => 'application/json',
            'X-Target-Environment' => 'sandbox',
            'X-Callback-Url' => $this->callbackUrl,
            'X-Reference-Id' => Str::uuid()->toString()
        ])->withBody(json_encode($requestBody), 'application/json')->post($this->requestToPay);


        return $response->status();
    }
}
