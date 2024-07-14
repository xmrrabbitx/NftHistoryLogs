<?php

namespace Nft\History\Methods\trxByHash;

use Nft\History\Methods\Topics;
use  Nft\History\Methods\trxByHash\trxByHashJson;
use Nft\History\Exec\singleThreadExec;
use Nft\History\Exec\multiThreadExec;

class trxByHash{

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider, $proxy){

        $this->contractAddress = $contractAddress;
        $this->proxy = $proxy;

        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);


    }

   /**
     * Method to retreive information of a transaction by using transaction hash
     * @param $trxHash is the transaction hash in hex format
     */
    function getTrxByHash($args){

        if(!empty($args)){
            $trxHash = $args[0];
        }else{
            throw $this->Exception("empty fields!");
        }

        $trxByHash = new trxByHashJson($this->contractAddress);
        $data = $trxByHash->getTrxByHashJson($trxHash);

        $result = $this->exec->singleExec($data);

        return $result;

    }

    private function Exception($string)
    {
        return $string;
    }

}