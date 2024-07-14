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

    function __construct($contractAddress, $provider, $proxy){

        $this->contractAddress = $contractAddress;
        $this->provider = $provider;
        $this->proxy = $proxy;
        
        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);
        $this->multi = new multiThreadExec($contractAddress, $provider, $this->proxy);

        $this->eventSig = new eventSig();

    }

    function getTopSellNfts($args){

        if(!empty($args) || $args === null){
            $mode = $args[0] ?? "singleThread";
            $countRank = $args[1] ?? "10";
            $fromBlock = $args[2] ?? "0x0";
            $toBlock = $args[3] ?? "latest";
        }else{
            throw $this->Exception("empty fields!");
        }

        $trxHashandIdsClass = new allTransferTrxHashAndIds($this->contractAddress, $this->provider, $this->proxy);
        $trxHashandIds = $trxHashandIdsClass->getAllTransferTrxHashAndIds([$fromBlock, $toBlock]);

        $topSellNftsJson = new topSellNftsJson();

        $weiToEther = new weiToEther();

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

          die('stopppp');

            /*
            //create the multiple cURL handle
            $cmi = curl_multi_init();

            $filter = array_map(function($logs) use($topSellNftsJson,&$cmi){

               return array_map(function($log) use($topSellNftsJson,&$cmi){

                    
                    $data = $topSellNftsJson->getTopSellNftsJson($log);

                    $ci = curl_init();

                    // set URL and other appropriate options
                    curl_setopt($ci, CURLOPT_URL, $this->provider);
                    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ci, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json'
                    ));
                    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, 0);

                    $json_data = json_encode($data);

                    # Send the cURL request with the JSON-RPC request in the body
                    curl_setopt($ci, CURLOPT_POST, true);
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $json_data);

                    // set Proxy for the Request
                    if( $this->proxy !== null){
                        curl_setopt($ci, CURLOPT_PROXY, $this->proxy);
                    }   
        
                    //add the two handles
                    curl_multi_add_handle($cmi,$ci);
                    
                    
                    return $ci;
                },$logs);
            },$trxHashandIds);     


            //execute the multi handle
            do {
                
               $status = curl_multi_exec($cmi, $active);
                if ($active) {
                    // Wait a short time for more activity
                    curl_multi_select($cmi);
                }
                sleep(2);
                
            } while ($active && $status == CURLM_OK);


            $getValues = array_map(function($logs){
               return array_map(function($log){

                    $re = array(curl_multi_getcontent($log));
                    $result = json_decode($re[0],true);

                    if(isset($result['result'])){

                        $value = $result['result']['value'];
                        
                        return $value;
                    }

                    curl_multi_remove_handle($cmi, $ci);
                    curl_multi_close($cmi);

                },$logs);    
            },$filter);

          
            $result = array_map(function($log) use($weiToEther) {

                $sum = array_sum(array_map('hexdec',$log));
               
                $nftSumPrice = $weiToEther->getWeiToEther([$sum]);
        
                return $nftSumPrice;
        
            },$getValues);

            */

        }

       
        arsort($result);


        return array_slice($result,0,$countRank);

 
    }

    private function Exception($string)
    {
        return $string;
    }
}