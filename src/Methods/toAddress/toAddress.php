<?php

namespace Nft\History\Methods\toAddress;

use Nft\History\Methods\toAddress\toAddressJson;
use Nft\History\Exec\singleThreadExec;
use Nft\History\Methods\eventSig\eventSig;


class toAddress{

    function __construct($contractAddress, $provider, $proxy){

        $this->proxy = $proxy;
        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

        $this->eventSig = new eventSig();

    }

    /**
     *  Method to get the receipt address of the transaction
     * @param string $eventName event signature e.g "Transfer"
     */
    function getToAddress($args){

        $trxHash = $args[0];
        if(isset($args[1])){
            $eventName = $args[1];
        }

        $toAddressJson = new toAddressJson();
        $data = $toAddressJson->getToAddressJson($trxHash);

        $result = $this->exec->singleExec($data);

        $columnsTopics = array_column($result['logs'], "topics"); 

        $fromAddress = array_column($columnsTopics, 2);

        if(isset($eventName) && $eventName != null){

            $event =  $this->eventSig->getEventSig([$eventName]);

            $filtered_topics = array_filter($columnsTopics, function($log) use($event) {
               
                return in_array($event, $log);
               
            });

            $resultTopics = $filtered_topics[key($filtered_topics)][2];

        }else{

            $topics = array_column($columnsTopics, 2);

            $resultTopics =  $topics;

        }

        return $resultTopics;

    }

   
}
