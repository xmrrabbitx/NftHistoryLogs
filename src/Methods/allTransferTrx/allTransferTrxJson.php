<?php

namespace Nft\History\Methods\allTransferTrx;

use Nft\History\Methods\eventSig\eventSig;

class allTransferTrxJson{

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
        $this->eventSig = new eventSig();
        $this->transferEventSig =$this->eventSig->getEventSig(["Transfer"]);

    }

    
    function getAllTransferTrx(){

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
                               $this->transferEventSig
                    ]
                ),
            ),
        );

        return $data;

    }
}