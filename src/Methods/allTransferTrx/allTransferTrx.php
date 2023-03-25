<?php

namespace Nft\History\Methods\allTransferTrx;

use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\allTransferTrx\allTransferTrxJson;
use Nft\History\Exec\singleThreadExec;

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
    function __construct($contractAddress, $provider){

        $this->contractAddress = $contractAddress;

        $this->exec = new singleThreadExec($contractAddress, $provider);

        
    }

    function getAllTransferTrx($args){

        $fromBlock = $args[0];
        $toBlock = $args[1];

        $allTransferTrx = new allTransferTrxJson($this->contractAddress, $fromBlock, $toBlock);
        $data = $allTransferTrx->getAllTransferTrx();

        $result = $this->exec->singleExec($data);
        return $result;

    }
}