<?php

namespace Nft\History\Methods\Transfer;

use Nft\History\Methods\Topics;

class transferTrxId{

    private $contractAddress;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress,$fromBlock,$toBlock){

        $this->contractAddress = $contractAddress;
        $this->fromBlock = $fromBlock;
        $this->toBlock = $toBlock;

        # call transfer event signature
        $this->transferEvenet = new Topics();
        $this->eventSig = $this->transferEvenet->eventsignature("Transfer");

    }

    function getTransferTrxById($tokenId){

        # Construct the JSON-RPC request
        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getLogs',
            'params' => array(
                array(            
                    "address" => $this->contractAddress,
                    "fromBlock" => $this->fromBlock,
                    "toBlock" =>  $this->toBlock,
                    "topics"=>[
                        $this->eventSig,
                        null,
                        null,
                        '0x' . str_pad(dechex($tokenId), 64, '0', STR_PAD_LEFT)
                    ]
                ),
            ),
        );

        return $data;

    }
}