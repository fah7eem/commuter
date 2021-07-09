<?php

class WhereIsMyTransportJourneys extends WhereIsMyTransport {
    public $data;
    function __construct($start, $destination){
        parent::__construct();
        $this->data = $this->getJourneys($start, $destination);
    }

    private function getJourneys(){
        $curl = curl_init();
        $body = '{
            "geometry": {
                "type": "MultiPoint",
                "coordinates": [
                    [
                        '.$start.'
                    ],
                    [
                        '.$destination.'
                    ]
                ]
            },
            "maxItineraries": 5
        }';
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->api_url.'journeys',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => $this->encoding,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$this->token,
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $httpcode == '200' ? json_decode($response) : false;
    }
}
