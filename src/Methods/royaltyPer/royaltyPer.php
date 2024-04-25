<?php

namespace Nft\History\Methods\royaltyPer;

use Nft\History\Methods\royaltyPer\royaltyPerJson;
use Nft\History\Exec\singleThreadExec;
use Nft\History\Exec\multiThreadExec;

/**
 * an interface to retrieve royalty percentage of an NFT
 */
class royaltyPer{

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider){

        $this->contractAddress = $contractAddress;

        $this->exec = new singleThreadExec($contractAddress, $provider);

    }

    /**
     * Method to retreive information of a royalty percentage of an NFT
     * @param 
     */
    function getRoyaltyPer(){

        $royaltyPerJson = new royaltyPerJson();
        $data = $royaltyPerJson->getRoyaltyPerJson();

        $result = $this->exec->singleExec($data);

        return $result;
    }
}
