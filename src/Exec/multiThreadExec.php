<?php


namespace Nft\History\Exec;

use \Exception as Exception;

class multiThreadExec{

    function __construct($contractAddress, $provider, $proxy=null){

        $this->contractAddress = $contractAddress;
        $this->provider = $provider;
        $this->proxy = $proxy;

    }

    function multiExecInit(){

       //create the multiple cURL handle
       return curl_multi_init();

    }

    function multiExecOpts($cmi, $data){

        //$cmi = $this->multiExecInit();
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $this->provider);
        curl_setopt($ch, CURLOPT_HEADER, 0);
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

        //add the two handles
        curl_multi_add_handle($cmi,$ch); 

        return$ch;
    }     

    function multiExec($data){


        $cmi = $this->multiExecInit();
        $handles = [];

        // Add handles to the multi cURL handle
        foreach ($data as $datum) {
            $handles[] = $this->multiExecOpts($cmi, $datum);
        }

        $running = null;
        // Execute the multi handle
        do {
            $status = curl_multi_exec($cmi, $running);
            if ($running) {
                curl_multi_select($cmi);
            }
        } while ($running > 0);

        // Collect responses
        $responses = [];
        foreach ($handles as $ch) {
            $responses[] = curl_multi_getcontent($ch);
            curl_multi_remove_handle($cmi, $ch);
            curl_close($ch);
        }

        // Close the multi cURL handle
        curl_multi_close($cmi);

        return $responses;
       
    }
    
}