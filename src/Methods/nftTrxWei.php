<?php

namespace Nft\History\Methods;

class nftTrxWei{

    private $transactionHash;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($transactionHash){

        $this->transactionHash = $transactionHash;

    }

    function getTrxWei(){

         # Construct the JSON-RPC request
         $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getTransactionReceipt',
            'params' => array(
                $this->transactionHash
            ),
        );

        return $data;
        
    }

    function checkTrxWei($data){

        $amountWei = ["value"=>array()];

        array_filter($data, function($log) use(&$amountWei){
           
            if (strlen($log) === 194) {

                    // Buy hash, sell hash, price
                    $arr = ["buyHash"=>array(),"sellHash"=>array(),"price"=>array()];
                    [$buyHash, $sellHash, $price] = str_split($log, 66);
                    $arr['buyHash'] = $buyHash;
                    $arr['sellHash'] = $sellHash;
                    $arr['price'] = $price;

                    array_push($amountWei['value'], $arr);

            } elseif (strlen($log) === 66) {

                    // uint256 wad
                    array_push($amountWei['value'],$log);

            } else {

                    // "0x" means zero
                    array_push($amountWei['value'],$log);

            }
        });
   
       return $amountWei;
       
    }
}