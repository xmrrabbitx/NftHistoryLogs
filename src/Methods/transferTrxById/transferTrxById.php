<?php

namespace Nft\History\Methods\transferTrxById;

use Nft\History\Exec\singleThreadExec;
use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\transferTrxById\transferTrxByIdJson;

class transferTrxById{

    private $contractAddress;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider){

        $this->contractAddress = $contractAddress;

        $this->exec = new singleThreadExec($contractAddress, $provider);

        # call transfer event signature
        $this->eventSig = new eventSig();
        $this->transferEventSig = $this->eventSig->getEventSig(["Transfer"]);

    }

    function getTransferTrxById($args){

        $tokenId = $args[0];
        $fromBlock = $args[1];
        $toBlock = $args[2];

        $transferTrxByIdJson = new transferTrxByIdJson($this->contractAddress);
        $data = $transferTrxByIdJson->getTransferTrxByIdJson($tokenId, $fromBlock, $toBlock);

        $result = $this->exec->singleExec($data);
        return $result;

    }
}