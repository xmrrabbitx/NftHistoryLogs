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
     * @param array $transactionHash hash of transaction
     */
    function fromAddress($transactionHash){

       
         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionReceipt',
            'params' => array(
                $transactionHash
            ),
        );

        return $data;
    }

    /**
     * @param array $transactionHash hash of transaction
     */
    function toAddress($transactionHash){

       
         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionReceipt',
            'params' => array(
                $transactionHash
            ),
        );

        return $data;
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
