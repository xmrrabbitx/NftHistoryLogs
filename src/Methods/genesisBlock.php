<?php

namespace Nft\History\Methods;

class genesisBlock{

    private $contractAddress;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress){
        
        $this->contractAddress = $contractAddress;

    }

    function getGenesisBlock(){

        # Construct the JSON-RPC request
        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getBlockByNumber',
            'params' => array("0x0",true
            ),
        );

        return $data;
    }
}