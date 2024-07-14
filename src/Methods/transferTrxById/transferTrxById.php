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
    function __construct($contractAddress, $provider, $proxy){

        $this->contractAddress = $contractAddress;
        $this->proxy = $proxy;

        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

        # call transfer event signature
        $this->eventSig = new eventSig();
        $this->transferEventSig = $this->eventSig->getEventSig(["Transfer"]);

    }

    /**
     * Method to get all transfer transaction of a specific NFT with token ID
     * @param int $tokenId The ID of the NFT token
     * @param hex $fromBlock 
     * @param hex $toBlock
     */
    function getTransferTrxById($args){

        if(!empty($args)){
            $tokenId = $args[0];
            $fromBlock = $args[2] ?? "0x0";
            $toBlock = $args[3] ?? "latest";
        }else{
            throw $this->Exception("empty fields!");
        }

        $transferTrxByIdJson = new transferTrxByIdJson($this->contractAddress);
        $data = $transferTrxByIdJson->getTransferTrxByIdJson($tokenId, $fromBlock, $toBlock);

        $result = $this->exec->singleExec($data);
        return $result;

    }

    private function Exception($string)
    {
        return $string;
    }
}