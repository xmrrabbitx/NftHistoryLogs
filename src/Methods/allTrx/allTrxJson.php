<?php

namespace Nft\History\Methods\allTrx;

class allTrxJson{

    private $contractAddress;
    private $fromBlock;
    private $toBlock;

    /**
     * @param $contractAddress the contract address of an nft
     * @param $fromBlock the begining block to get logs
     * @param $toBlock the destination block to get logs
     */
    function __construct($contractAddress){

        $this->contractAddress = $contractAddress;

    }

    public function getAllTrxJson($fromBlock, $toBlock){
        
        # Construct the JSON-RPC request
        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getLogs',
            'params' => array(
                array(
                    "address" => $this->contractAddress,
                    "fromBlock" => $fromBlock,
                    "toBlock" =>  $toBlock
                )
            )
        );

        return $data;
    }



}