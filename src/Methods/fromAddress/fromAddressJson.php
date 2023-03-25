<?php

namespace Nft\History\Methods\fromAddress;

class fromAddressJson{

    /**
     * @param array $transactionHash hash of transaction
     */
    function getFromAddressJson($transactionHash){

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
