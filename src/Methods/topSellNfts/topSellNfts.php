<?php

namespace Nft\History\Methods\topSellNfts;

use Nft\History\Exec\singleThreadExec;
use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\weiToEther\weiToEther;
use Nft\History\Methods\trxByHash\trxByHash;
use Nft\History\Methods\topSellNfts\topSellNftsJson;
use Nft\History\Methods\allTransferTrxHashAndIds\allTransferTrxHashAndIds;


class topSellNfts{

    function __construct($contractAddress, $provider, $proxy){

        $this->contractAddress = $contractAddress;
        $this->provider = $provider;
        $this->proxy = $proxy;
        
        $this->exec = new singleThreadExec($contractAddress, $provider, $this->proxy);

        $this->eventSig = new eventSig();

    }

    function getTopSellNfts($args){

        if(!empty($args) || $args === null){
            $mode = $args[0] ?? "singleThread";
            $countRank = $args[1] ?? "10";
            $fromBlock = $args[2] ?? "0x0";
            $toBlock = $args[3] ?? "latest";
        }else{
            throw $this->Exception("empty fields!");
        }

        $trxHashandIdsClass = new allTransferTrxHashAndIds($this->contractAddress, $this->provider, $this->proxy);
        $trxHashandIds = $trxHashandIdsClass->getAllTransferTrxHashAndIds([$fromBlock, $toBlock]);

        $topSellNftsJson = new topSellNftsJson();

        $weiToEther = new weiToEther();

           
        $filterData = array_map(function($log) use($topSellNftsJson){
                
            return array_map(function($logs) use($topSellNftsJson){
                        
                   $data = $topSellNftsJson->getTopSellNftsJson($logs);
                    
                   $result = $this->exec->singleExec($data);

                   if($result['value'] !== '0x0'){

                         return $result['value'];

                   }
            },$log);

        },$trxHashandIds);
            
        $result = array_map(function($log) use($weiToEther) {

                $sum = array_sum(array_map('hexdec',$log));
               
                return $weiToEther->getWeiToEther([$sum]);

        },$filterData);


        arsort($result);


        return array_slice($result,0,$countRank);

 
    }

    private function Exception($string)
    {
        return $string;
    }
}