<?php

namespace Nft\History\Methods\royaltyPer;

use kornrunner\Keccak;

class royaltyPerJson{

    function __construct(){

    }

    /**
     * Method to retreive information of a royalty percentage of an NFT
     */
    function getRoyaltyPerJson(){

        $hash = Keccak::hash('royaltyInfo(uint256,uint256)', 256);
        $functionSelector = substr($hash, 0, 8);
        $tokenIdHex = str_pad(dechex('19400010303'), 64, "0", STR_PAD_LEFT);
        $valueHex = str_pad(dechex('0'), 64, "0", STR_PAD_LEFT);
        //print(strlen($data));

        $data = '0x' . $functionSelector . $tokenIdHex . $valueHex;

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_call',
            'params' => array([
                    'from'=>'0x17084972D8f7Aa569E6d81766f433443474463B0',
                    'to'=> '0x452270bC08D924E0f0dEb455BdbDB27FF0c642B8',
                    'data'=> $data
                ],
            'latest'
            ),
        );

        return $data;

    }
}