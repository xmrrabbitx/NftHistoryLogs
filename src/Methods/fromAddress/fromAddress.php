<?php

namespace Nft\History\Methods\fromaddress;

use Nft\History\Exec\singleThreadExec;
use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\fromAddress\fromAddressJson;

class fromAddress{

    function __construct($contractAddress, $provider){

        $this->exec = new singleThreadExec($contractAddress, $provider);

        $this->eventSig = new eventSig();
    }
   
    /**
     * @param array $transactionHash hash of transaction
     */
    function getFromAddress($args){

        $trxHash = $args[0];
        if(isset($args[1])){
            $eventName = $args[1];
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

}
