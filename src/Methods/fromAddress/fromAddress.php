<?php

namespace Nft\History\Methods\fromaddress;

use Nft\History\Exec\singleThreadExec;
use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\fromAddress\fromAddressJson;

class fromAddress{

    function __construct($contractAddress, $provider, $proxy){

        $this->proxy = $proxy;
        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

        $this->eventSig = new eventSig();
    }
   
     /**
     * Method to get the sender address of the transaction
     * @param array $trxHash is the hash of transaction
     * @param string $eventName event signature e.g "Transfer"
     */
    function getFromAddress($args){

        if(!empty($args)) {
            $trxHash = $args[0];
            if(isset($args[1])){
                $eventName = $args[1];
            }
        }else{
            throw $this->Exception("empty fields!");
        }


        $fromAddressJson = new fromAddressJson();
        $result = $fromAddressJson->getFromAddressJson($trxHash);

        $res = $this->exec->singleExec($result);

        $columnsTopics = array_column($res['logs'], "topics"); 

        $fromAddress = array_column($columnsTopics, 1);

        if(isset($eventName) && $eventName != null){
            
            $event =  $this->eventSig->getEventSig([$eventName]);
            
            $filtered_topics = array_filter($columnsTopics, function($log) use($event) {
               
                return in_array($event, $log);
               
            });
            
            $resultTopics = $filtered_topics[key($filtered_topics)][1];

        }else{

            $topics = array_column($columnsTopics, 1);

            $resultTopics =  $topics;

        }

        return $resultTopics;

    }

    private function Exception($string)
    {
        return $string;
    }

}
