<?php

class WhereIsMyTransport {

    protected  $auth_url = WMT_AUTH_URL;
    protected  $api_url = WMT_API_URL;
    protected  $agency_id = WMT_AGENCY;
    protected  $token;
    function __construct(){
        if(!isset($_COOKIE['WMT_TOKEN'])){
            $this->authenticate();
        }
        $this->token = isset($_COOKIE['WMT_TOKEN']) ? $_COOKIE['WMT_TOKEN'] : false;
    }

    private function newToken(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->auth_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => 'gzip',
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'client_id='.WMT_CLIENT_ID.'&client_secret='.urlencode(WMT_CLIENT_SECRET).'&grant_type=client_credentials',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
        ),
        ));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $httpcode == '200' ? json_decode($response) : false;
    }

    private function authenticate(){
        $token = $this->newToken();
        if($token !== false){
            setcookie('WMT_TOKEN', $token->access_token, time() + $token->expires_in, "/");
        }else{
            throw new Exception('Unable to connect to resource');
        }
    }
}