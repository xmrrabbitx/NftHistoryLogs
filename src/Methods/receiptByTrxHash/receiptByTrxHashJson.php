<?php

namespace Nft\History\Methods\receiptByTrxHash;

use Nft\History\Methods\Topics;

class receiptByTrxHashJson{

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct(){

        // 

    }

    /**
     * Method to retreive information of a receipent transaction
     * @param $trxHash is the transaction hash in hex format
     */
    function getReceiptByTrxHashJson($trxHash){

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionReceipt',
            'params' => array(
                            $trxHash
            ),
        );

        return $data;

    }
}