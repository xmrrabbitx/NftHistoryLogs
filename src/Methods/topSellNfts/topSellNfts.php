<?php

namespace Nft\History\Methods\topSellNfts;

use Nft\History\Exec\singleThreadExec;
use Nft\History\Exec\multiThreadExec;
use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\weiToEther\weiToEther;
use Nft\History\Methods\trxByHash\trxByHash;
use Nft\History\Methods\topSellNfts\topSellNftsJson;
use Nft\History\Methods\allTransferTrxHashAndIds\allTransferTrxHashAndIds;


class topSellNfts{

    function __construct($contractAddress, $provider){

        $this->contractAddress = $contractAddress;
        $this->provider = $provider;
        
        $this->exec = new singleThreadExec($contractAddress, $provider);
        $this->multi = new multiThreadExec($contractAddress, $provider);

        $this->eventSig = new eventSig();

    }

    function getTopSellNfts($args){

        $mode = $args[0];

        $trxHashandIdsClass = new allTransferTrxHashAndIds($this->contractAddress, $this->provider);
        $trxHashandIds = $trxHashandIdsClass->getAllTransferTrxHashAndIds();

        
        $topSellNftsJson = new topSellNftsJson();

        $weiToEther = new weiToEther();
        
        $start = microtime(true);
        //$mh = $this->multi->multiExecInit();
        
        if($mode === "singleThread"){
           
            $filterData = array_map(function($log) use($topSellNftsJson){
                
                return array_map(function($logs) use($topSellNftsJson){
                        
                        $data = $topSellNftsJson->getTopSellNftsJson($logs);
                    
                        $result = $this->exec->singleExec($data);

                        if($result['value'] !== '0x0'){

                                   $logs = $result['value'];
                                   return $logs;

                                   }
                },$log);

            },$trxHashandIds);
            
            $result = array_map(function($log) use($weiToEther) {

                $sum = array_sum(array_map('hexdec',$log));
               
                        $nftSumPrice = $weiToEther->getWeiToEther([$sum]);
        
                        return $nftSumPrice;
        
            },$filterData);
        }

        elseif($mode === "multiThread"){
            //create the multiple cURL handle
            $mh = curl_multi_init();

            $arr = [0=>["https://dummyjson.com/products/1"],1=>["https://dummyjson.com/products/2"]];
            
            // $aCurlHandles = array();

            $filter = array_map(function($logs) use($topSellNftsJson,&$mh){

               return array_map(function($log) use($topSellNftsJson,&$mh){

                    
                    $data = $topSellNftsJson->getTopSellNftsJson($log);

                    $ch = curl_init();

                    // set URL and other appropriate options
                    curl_setopt($ch, CURLOPT_URL, $this->provider);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json'
                    ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                    $json_data = json_encode($data);

                    # Send the cURL request with the JSON-RPC request in the body
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

                    
                    //add the two handles
                    curl_multi_add_handle($mh,$ch);
                    
                
                        // $this->multi->multiExecOpts($data,$mh,$aCurlHandles);
                        //var_dump($id);
                    
                    return $ch;
                },$logs);
            },$trxHashandIds);     


            //execute the multi handle
            do {
                
               $status = curl_multi_exec($mh, $active);
                if ($active) {
                    // Wait a short time for more activity
                    curl_multi_select($mh);
                }
                sleep(2);
                
            } while ($active && $status == CURLM_OK);


            $getValues = array_map(function($logs){
               return array_map(function($log){

                    $re = array(curl_multi_getcontent($log));
                    $result = json_decode($re[0],true);

                    if(isset($result['result'])){
                        $value = $result['result']['value'];
                        //$nftSumPrice = $weiToEther->getWeiToEther([$sum]);
                        return $value;
                    }

                    curl_multi_remove_handle($mh, $ch);
                    curl_multi_close($mh);

                },$logs);    
            },$filter);

          
            $result = array_map(function($log) use($weiToEther) {

                $sum = array_sum(array_map('hexdec',$log));
               
                $nftSumPrice = $weiToEther->getWeiToEther([$sum]);
        
                return $nftSumPrice;
        
            },$getValues);

        }

       
        arsort($result);
        // $this->multi->multiExec($mh);
        $end = microtime(true);
        $t = $end - $start;
        print($t);
        

       return $result;
 
    }
}