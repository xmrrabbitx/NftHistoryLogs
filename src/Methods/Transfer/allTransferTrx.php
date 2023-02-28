<?php

namespace Nft\History\Methods\Transfer;

use Nft\History\Methods\Topics;

class allTransferTrx{

    private $contractAddress;
    private $fromBlock;
    private $toBlock;
    private $transferEvenet;
    private $eventSig;

    /**
     * @param string $contractAddress the contract address of an nft
     * @param hex $fromBlock the begining block to get logs
     * @param hex $toBlock the destination block to get logs
     */
    function __construct($contractAddress,$fromBlock,$toBlock){

        $this->contractAddress = $contractAddress;
        $this->fromBlock = $fromBlock;
        $this->toBlock = $toBlock;

        # call transfer event signature
        $this->transferEvenet = new Topics();
        $this->eventSig = $this->transferEvenet->eventsignature("Transfer");

    }

    
    function getTransferTrx(){

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
                               $this->eventSig
                    ]
                ),
            ),
        );

        return $data;

    }
}