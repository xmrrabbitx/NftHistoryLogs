<?php

namespace Nft\History\Methods\genesisBlock;

use Nft\History\Methods\genesisBlock\genesisBlockJson;
use Nft\History\Exec\singleThreadExec;
use Nft\History\Exec\multiThreadExec;

class genesisBlock{

    private $contractAddress;

    /**
     * the first block of smart contract
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider){
        
        $this->contractAddress = $contractAddress;

        $this->exec = new singleThreadExec($contractAddress, $provider);

    }

    function getGenesisBlock(){

        $genesis = new genesisBlockJson($this->contractAddress);
        $data = $genesis->getGenesisBlockJson();

        $result = $this->exec->singleExec($data);
        return $result;

    }
}