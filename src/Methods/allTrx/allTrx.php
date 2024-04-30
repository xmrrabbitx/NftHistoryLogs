<?php

namespace Nft\History\Methods\allTrx;

use Nft\History\Methods\allTrx\allTrxJson;
use Nft\History\Exec\singleThreadExec;

class allTrx{

    private $contractAddress;
    private $fromBlock;
    private $toBlock;
  
    /**
     * @param $contractAddress the contract address of an nft
     * @param $fromBlock the begining block to get logs
     * @param $toBlock the destination block to get logs
     */
    function __construct($contractAddress, $provider, $proxy){

        $this->contractAddress = $contractAddress;
        $this->proxy = $proxy;
        
        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

    }

    /**
     * Method to get all transactions history of an NFT contract
     */
    public function getAllTrx($args){

        $fromBlock = $args[0];
        $toBlock = $args[1];

        $allTrxJson = new allTrxJson($this->contractAddress);
        $data = $allTrxJson->getAllTrxJson($fromBlock, $toBlock);

        $result = $this->exec->singleExec($data);
        return $result;
        
    }



}