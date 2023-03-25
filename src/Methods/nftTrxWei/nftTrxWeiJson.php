<?php

namespace Nft\History\Methods\nftTrxWei;

class nftTrxWeiJson{

    private $transactionHash;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct(){

    }

    /**
     * Method to get the value of transaction in WEI format
     */
    function getNftTrxWeiJson($TrxHash){

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionReceipt',
            'params' => array(
                $TrxHash
            ),
        );

        return $data;
        
    }
}