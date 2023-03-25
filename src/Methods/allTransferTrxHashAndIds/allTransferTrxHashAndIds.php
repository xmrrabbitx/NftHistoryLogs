<?php

namespace Nft\History\Methods\allTransferTrxHashAndIds;

use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Exec\singleThreadExec;
use Nft\History\Exec\multiThreadExec;

class allTransferTrxHashAndIds{

    private $contractAddress;

    /**
     * @param $contractAddress the contract address of an nft
     */
    function __construct($contractAddress, $provider){

        $this->contractAddress = $contractAddress;

        # call transfer event signature
        $this->eventSig = new eventSig();
        $this->transferEventSig = $this->eventSig->getEventSig(["Transfer"]);

        $this->exec = new singleThreadExec($contractAddress, $provider);

    }

    /**
     * Method to rearrange the transaction hashes by ids
     */
    function getAllTransferTrxHashAndIds(){

        if(empty($args)){
            $fromBlock = "0x0";
            $toBlock = "latest";
        }
        
        $allTransferTrxHashByIdsJson = new allTransferTrxHashAndIdsJson($this->contractAddress);
        $getAllTransferTrxHashByIds = $allTransferTrxHashByIdsJson->getAllTransferTrxHashAndIdsJson($fromBlock, $toBlock);
        $getAllTransferTrxHashByIds = $this->exec->singleExec($getAllTransferTrxHashByIds);
        
        $filtered_topics = array_filter($getAllTransferTrxHashByIds, function($log) use(&$res){
        
          if($res && array_key_exists(hexdec($log['topics'][3]),$res)){
            
            array_push($res[hexdec($log['topics'][3])],$log['transactionHash']);

          }else{

            $res[hexdec($log['topics'][3])] = array($log['transactionHash']);
            
          }
         
        });
        
        $result = $res;
        
        return $result;
    }
}