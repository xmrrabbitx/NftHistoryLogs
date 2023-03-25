<?php

namespace Nft\History\Methods\allTransferTrxHashAndIds;

use Nft\History\Methods\eventSig\eventSig;

class allTransferTrxHashAndIdsJson{

    private $contractAddress;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress){

        $this->contractAddress = $contractAddress;

        # call transfer event signature
        $this->eventSig = new eventSig();
        $this->transferEventSig = $this->eventSig->getEventSig(["Transfer"]);

    }

    /**
     * Method to rearrange the transaction hashes by ids
     */
    function getAllTransferTrxHashAndIdsJson($fromBlock, $toBlock){

        # Construct the JSON-RPC request
        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getLogs',
            'params' => array(
                array(            
                    "address" => $this->contractAddress,
                    "fromBlock" => $fromBlock,
                    "toBlock" =>  $toBlock,
                    "topics"=>[
                        $this->transferEventSig
                    ]
                ),
            ),
        );

        return $data;

    }
}