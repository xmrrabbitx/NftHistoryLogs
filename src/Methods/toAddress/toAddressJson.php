<?php

namespace Nft\History\Methods\toAddress;

class toAddressJson{

    /**
     * @param array $transactionHash hash of transaction
     */
    function getToAddressJson($transactionHash){

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionReceipt',
            'params' => array(
                $transactionHash
            ),
        );

        return $data;
    }

   
}
