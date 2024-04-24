<?php

namespace Nft\History\Methods\royaltyPer;

use kornrunner\Keccak;

class receiptByTrxHashJson{

    function __construct(){

    }

    /**
     * Method to retreive information of a royalty percentage of an NFT
     */
    function getRoyaltyPerJson(){

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_call',
            'params' => array(
                     
            ),
        );

        return $data;

    }
}
