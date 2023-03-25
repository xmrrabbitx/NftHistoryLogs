<?php

namespace Nft\History\Methods\eventSig;

use Nft\History\Methods\eventSig\eventSignature;

class eventSig{


    function __construct(){

    }

    /**
     * Method to retrieve 256 bytes of an event name
     * * @param string $eventName is the name of event in contract address e.g "transfer"
     */
    function getEventSig($args){

        $eventName = $args[0];

        $evenSig = new eventSignature();
        $result = $evenSig->getEventSignature($eventName);

        return $result;

    }
}
