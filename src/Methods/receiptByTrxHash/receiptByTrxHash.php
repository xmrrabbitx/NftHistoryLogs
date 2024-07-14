<?php

namespace Nft\History\Methods\receiptByTrxHash;

use Nft\History\Methods\Topics;
use Nft\History\Methods\receiptByTrxHash\receiptByTrxHashJson;
use Nft\History\Exec\singleThreadExec;
use Nft\History\Exec\multiThreadExec;

class receiptByTrxHash{

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider, $proxy){
     
        $this->contractAddress = $contractAddress;
        $this->proxy = $proxy;
     
        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

    }

    /**
     * Method to retreive information of a receipent transaction
     * @param $trxHash is the transaction hash in hex format
     */
    function getReceiptByTrxHash($args){

        if(!empty($args)) {
            $trxHash = $args;
        }else{
            throw $this->Exception("empty fields!");
        }

        $receiptByTrxHashJson = new receiptByTrxHashJson();
        $data = $receiptByTrxHashJson->getReceiptByTrxHashJson($trxHash);

        $result = $this->exec->singleExec($data);

        return $result;
    }

    private function Exception($string)
    {
        return $string;
    }

}