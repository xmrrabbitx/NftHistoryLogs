<?php

namespace Nft\History\Methods\nftTrxWei;

use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Exec\singleThreadExec;

class nftTrxWei{

    private $transactionHash;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider, $proxy){

        $this->contractAddress = $contractAddress;
        $this->proxy = $proxy;

        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

        $this->eventSig = new eventSig();
    }

    /**
     * Method to get the value of transaction in WEI format
     * @param hex $transactionHash your desired transaction hash 
     * @param string $eventName event signature name that you want to filter by
     */
    function getNftTrxWei($args){

        if(!empty($args)) {
            $trxHash = $args[0];

            if(isset($args[1])){
                $eventName = $args[1];
            }
        }else{
            throw $this->Exception("empty fields!");
        }


        $nftTrxWeiJson = new nftTrxWeiJson();
        $amount = $nftTrxWeiJson->getNftTrxWeiJson($trxHash);
        $result = $this->exec->singleExec($amount);

        $columnsTopics = array_column($result['logs'], "topics");
        $topics = array_column($columnsTopics, 0);

        $data = array_column($result['logs'], "data");

        if(isset($eventName) && $eventName != null){

           
            $event =  $this->eventSig->getEventSig([$eventName]);
            
            $filtered_topics = array_filter($result['logs'], function($log) use($event) {
               
                return in_array($event, $log['topics']);
               
            });

            $filteredData = [];   
            $filtered_data = array_filter($filtered_topics, function($log) use(&$filteredData) {
            
               array_push($filteredData, $log['data']);

            });

            $checkTrxWei = $this->checkTrxWei($filteredData);

        }else{

            $filteredData = []; 
            $filtered_topics = array_filter($result['logs'], function($log) use(&$filteredData) {
               
                 array_push($filteredData, $log['data']);
               
            });

            $checkTrxWei = $this->checkTrxWei($filteredData);

        }

        return $checkTrxWei;
        
    }

    /**
     * Method to check lenght of data transaction
     */
    function checkTrxWei($data){

        $amountWei = ["value"=>array()];

        array_filter($data, function($log) use(&$amountWei){
           
            if (strlen($log) === 194) {

                    // Buy hash, sell hash, price
                    $arr = ["buyHash"=>array(),"sellHash"=>array(),"price"=>array()];
                    [$buyHash, $sellHash, $price] = str_split($log, 66);
                    $arr['buyHash'] = $buyHash;
                    $arr['sellHash'] = $sellHash;
                    $arr['price'] = $price;

                    array_push($amountWei['value'], $arr);

            } elseif (strlen($log) === 66) {

                    // uint256 wad
                    array_push($amountWei['value'],$log);

            } else {

                    // "0x" means zero
                    array_push($amountWei['value'],$log);

            }
        });
   
       return $amountWei;
       
    }

    private function Exception($string)
    {
        return $string;
    }
}