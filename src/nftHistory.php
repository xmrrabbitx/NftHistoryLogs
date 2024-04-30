<?php
/**
 * A class that provides methods to retrieve transaction history 
 * for a given NFT contract address
 * @package Nft\History
*/
namespace Nft\History;

use \Exception as Exception;
use Nft\History\Methods\allTrx\allTrx;
use Nft\History\Methods\allTransferTrx\allTransferTrx;
use Nft\History\Methods\Transfer\transferTrxId;
use Nft\History\Methods\receiptByTrxHash\receiptByTrxHash;
use Nft\History\Methods\topNfts;
use Nft\History\Methods\eventSig\eventSig;
use Nft\History\Methods\fromAddress\fromAddress;
use Nft\History\Methods\genesisBlock;
use Nft\History\Methods\nftTrxWei;
use Nft\History\Methods\allTransferTrxHashAndIds;
use Nft\History\Methods\trxByHash\trxByHash;
use Nft\History\Methods\trxByHash\topSellNfts;
use Nft\History\Methods\transferRoyality\transferRoyality;

final class nftHistory{

    /**
     * @var int $tokenId the id of an nft  
     */
    protected $tokenId;

    /**
     * @var string $contractAddress the contract address of an nft  
     */ 
    protected $contractAddress;

    /**
     * @var string $provider The Ethereum node URL to connect to
     */

    /**
     * Constructor function to set up the NFT contract address and 
     * Ethereum node URL
     * 
     * @param string $contractAddress The address of the NFT contract
     * @param string $provider The Ethereum node URL to connect to
     */
    function __construct($contractAddress, $provider, $proxy=null){

       if (is_string($provider) && (filter_var($provider, FILTER_VALIDATE_URL) !== false)) {

            $this->contractAddress = $contractAddress;
            $this->provider = $provider;


            if($proxy !== null && filter_var($proxy, FILTER_VALIDATE_URL)){
                $this->proxy = $proxy;
            }else{
                $this->proxy = null;
            }
            

       }

    }

    function __call($name, $arguments){
        
        if (empty($this->provider)) {
            throw new \RuntimeException('Please set provider first!');
        }

        $className = sprintf("Nft\History\Methods\%s\%s" , $name, $name);
        $classNameObj = new $className($this->contractAddress, $this->provider, $this->proxy);
        $methodName = "get" . ucfirst($name);
        $result = $classNameObj->$methodName($arguments);
        
        return $this->result($result);
         
    }

    function result($result){

        return $result;

    }
}