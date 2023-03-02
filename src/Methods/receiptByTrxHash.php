<?php

namespace Nft\History\Methods;

use Nft\History\Methods\Topics;

class receiptByTrxHash{

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct(){

    }

    function getReceiptByTrxHash($trxHash){

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