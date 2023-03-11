<?php

namespace Nft\History\Methods;

use Nft\History\Methods\Topics;

class trxByHash{

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct(){

    }

    /**
     * Method to retreive information of a transaction by using transaction hash
     * @param $trxHash is the transaction hash in hex format
     */
    function getTrxByHash($trxHash){

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionByHash',
            'params' => array(
                            $trxHash
            ),
        );

        return $data;

    }
}