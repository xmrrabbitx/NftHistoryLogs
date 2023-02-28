<?php

namespace Nft\History\Methods;

class allTrx{

    private $contractAddress;
    private $fromBlock;
    private $toBlock;

    /**
     * @param $contractAddress the contract address of an nft
     * @param $fromBlock the begining block to get logs
     * @param $toBlock the destination block to get logs
     */
    function __construct($contractAddress,$fromBlock,$toBlock){

        $this->contractAddress = $contractAddress;
        $this->fromBlock = $fromBlock;
        $this->toBlock = $toBlock;

    }

    public function getAllTrx(){
        
        # Construct the JSON-RPC request
        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getLogs',
            'params' => array(
                array(
                    "address" => $this->contractAddress,
                    "fromBlock" => $this->fromBlock,
                    "toBlock" =>  $this->toBlock
                )
            )
        );

        return $data;
    }



}