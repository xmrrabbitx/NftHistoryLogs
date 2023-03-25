<?php

$arr = [0=>["https://dummyjson.com/products/1","https://dummyjson.com/products/3"],1=>["https://dummyjson.com/products/2"]];
$mh = curl_multi_init();

$filter = array_map(function($log) use(&$mh){

   return array_map(function($logs) use(&$mh){
            
        $ch = curl_init();
                    // set URL and other appropriate options
                    curl_setopt($ch, CURLOPT_URL, $logs);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json'
                    ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                    
                    //add the two handles
                    curl_multi_add_handle($mh,$ch);

                    return $ch;

    },$log);

},$arr);

do {
                
    $status = curl_multi_exec($mh, $active);
     if ($active) {
         // Wait a short time for more activity
         curl_multi_select($mh);
     }
     
 } while ($active && $status == CURLM_OK);

array_map(function($log){

    array_map(function($logs){

        
            $res = curl_multi_getcontent($logs);
            var_dump($res);
         

    },$log);

},$filter);