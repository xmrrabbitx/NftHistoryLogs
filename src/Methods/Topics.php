<?php

namespace Nft\History\Methods;

use kornrunner\Keccak;


class Topics{

    private $eventName;

    /**
     * @param string $eventName is the name of event in contract address e.g "transfer"
     */
    public function eventsignature($eventName){

        $eventsig = ["Transfer" => "Transfer(address,address,uint256)",
                     "ApprovalForAll" => "ApprovalForAll(address,address,bool)",
                     "Approval" => "Approval(address,address,uint256)",
                     "OrdersMatched" => "OrdersMatched(bytes32,bytes32,address,address,uint256,bytes32)"                                                   
                                                                        ];

        return "0x" . Keccak::hash($eventsig[$eventName], 256);

    }

    /**
     * @param array $topics topics of a contract address
     */
    function fromAddress($topics){

        return $topics[1];
    }

    /**
     * @param array $topics topics of a contract address
     */
    function toAddress($topics){

        return $topics[2];
    }

    /**
     * @param array $topics topics of a contract address
     */
    function nftTokenId($topics){

        # getting topic index 3
        $decodedInputData = $topics[3];

        # extract token ID from input data
        $tokenId = hexdec($decodedInputData);

        return $tokenId;

    }
}
