<?php


namespace Nft\History\Exec;

use \Exception as Exception;

class multiThreadExec{

    function __construct($contractAddress, $provider){

        $this->contractAddress = $contractAddress;
        $this->provider = $provider;
        
    }

    function multiExecInit(){

       //create the multiple cURL handle
       $mh = curl_multi_init();

       return $mh;

    }

    function multiExecOpts($data,$mh,$acurl){

        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $this->provider);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $json_data = json_encode($data);

        # Send the cURL request with the JSON-RPC request in the body
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $aCurlHandles[$id] = $ch;
        //add the two handles
        curl_multi_add_handle($mh,$ch); 
        
    }     

    function multiExec($mh){

        
        
       
    }
    
}