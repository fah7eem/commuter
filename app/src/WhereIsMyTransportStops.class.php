<?php

Class WhereIsMyTransportStops extends WhereIsMyTransport{
    public $data;
    function __construct(){
        parent::__construct();
        $this->data = $this->getStops();
    }

    private function getStops(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->api_url.'stops?agencies='.$this->agency_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => $this->encoding,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$this->token
        ),
        ));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($httpcode == '429'){
            setcookie('WMT_TOKEN', '', time() - 3600 , "/");
        }
        curl_close($curl);
        return $httpcode == '200' ? json_decode($response) : false;
    }
}