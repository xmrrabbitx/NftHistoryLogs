<?php

namespace Nft\History\Exec;

use \Exception as Exception;

class singleThreadExec{

    function __construct($contractAddress, $provider, $proxy=null){

        $this->contractAddress = $contractAddress;
        $this->provider = $provider;
        $this->proxy = $proxy;

    }

    function singleExec($data){

        # Set up the cURL request
        $ch = curl_init($this->provider);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // set Proxy for the Request
        if( $this->proxy !== null){
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        $json_data = json_encode($data);

        # Send the cURL request with the JSON-RPC request in the body
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error = curl_error($curl);
        }

         //print_r($response);

        # error checking
        if (!empty($response)) {
            $decoded_response = json_decode($response, true);
            if (isset($decoded_response['error'])) {
                throw new Exception($decoded_response['error']['message']);
            }
            $result = $decoded_response['result'];
           
        } else {
            throw new Exception("Empty response received.");
        }
        
        # Parse the response to extract the transaction history
        $history = json_decode($response, true)['result'];

        return $history;
    }
}