<?php

namespace Nft\History\Methods\eventSig;

use kornrunner\Keccak;

class eventSignature{

    
    private $eventName;

    /**
     * Method to retrieve 256 bytes of an event name
     * @param string $eventName is the name of event in contract address e.g "transfer"
     */
    public function getEventSignature($eventName){
        
        $eventsig = ["Transfer" => "Transfer(address,address,uint256)",
                     "ApprovalForAll" => "ApprovalForAll(address,address,bool)",
                     "Approval" => "Approval(address,address,uint256)",
                     "OrdersMatched" => "OrdersMatched(bytes32,bytes32,address,address,uint256,bytes32)"                                                   
                                                                        ];

        return "0x" . Keccak::hash($eventsig[$eventName], 256);

    }

}