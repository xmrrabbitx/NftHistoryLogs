<?php
/**
 * A class that provides methods to retrieve transaction history 
 * for a given NFT contract address
 * @package Nft\History
*/
namespace Nft\History;

use \Exception as Exception;
use Nft\History\Methods\Topics;
use Nft\History\Methods\allTrx;
use Nft\History\Methods\Transfer\transferTrxId;
use Nft\History\Methods\Transfer\allTransferTrx;
use Nft\History\Methods\genesisBlock;
use Nft\History\Methods\receiptByTrxHash;
use Nft\History\Methods\nftTrxWei;

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
    function __construct($contractAddress, $provider){

       if (is_string($provider) && (filter_var($provider, FILTER_VALIDATE_URL) !== false)) {

            $this->contractAddress = $contractAddress;
            $this->provider = $provider;

       }
    }

    /**
     * Method to get all transactions history of an NFT contract
     */
    function allTrx($fromBlock,$toBlock){
    
        $allTrx = new allTrx($this->contractAddress,$fromBlock,$toBlock);
        $data = $allTrx->getAllTrx();

        $result = $this->exec($data);
        return $this->result($result);

    }

    /**
    * Method to get all transfer transactions of an NFT contract
    */
    function allTransferTrx($fromBlock, $toBlock){

        $allTransferTrx = new allTransferTrx($this->contractAddress,$fromBlock,$toBlock);
        $data = $allTransferTrx->getTransferTrx();

        $result = $this->exec($data);
        return $this->result($result);

    }

    /**
     * Method to get all transfer transaction of a specific NFT with token ID
     *
     * @param int $tokenId The ID of the NFT token
     */
    function transferTrxById($tokenId, $fromBlock, $toBlock){

        $transferTrxById = new transferTrxId($this->contractAddress,$fromBlock,$toBlock);
        $data = $transferTrxById->getTransferTrxById($tokenId);

        $result = $this->exec($data);
        return $this->result($result);

    }

     /**
     * Method to get all Orders transaction of a specific NFT with token ID
     *
     * @param int $tokenId The ID of the NFT token
     */
    function receiptByTrxHash($trxHash){

        $receiptByTrxHash = new receiptByTrxHash();
        $data = $receiptByTrxHash->getReceiptByTrxHash($trxHash);

        $result = $this->exec($data);
        return $this->result($result);

    }

    /**
     * Method to get desired event signature
     *
     * @param string $eventName is the name of event e.g "Transfer"
     */
    function eventSig($eventName){

        $evenSig = new Topics();
        $result = $evenSig->eventSignature($eventName);

        return $result;

    }

    /**
     * Method to get the sender address of the transaction
     * @param array $topics is the array of all topics
     */
    function fromAddress($topics){

        $fromAddress = new Topics();
        $result = $fromAddress->fromAddress($topics);

        return $result;
    }

    /**
     *  Method to get the receipt address of the transaction
     * @param array $topics is the array of all topics
     */
    function toAddress($topics){

        $toAddress = new Topics();
        $result = $toAddress->toAddress($topics);

        return $result;

    }

    /**
     * obtain token id based on topics
     * @param array $topics is the array of all topics
     */
    function tokenId($topics){

        $tokenId = new Topics();
        $result = $tokenId->nftTokenId($topics);

        return $result;

    }

    /**
     * the first Block
     */
    function genesisBlock(){

        $genesis = new genesisBlock($this->contractAddress);
        $data = $genesis->getGenesisBlock();

        $result = $this->exec($data);
        return $this->result($result);

    }

    /**
     * @param hex $transactionHash your desired transaction hash 
     * @param string $eventName event signature name that you want to filter by
     */
    function nftTrxWei($transactionHash, $eventName=null){

        $nftTrxWei = new nftTrxWei($transactionHash);
        $amount = $nftTrxWei->getTrxWei();
        $result = $this->exec($amount);
        
        $columnsTopics = array_column($result['logs'], "topics");
        $topics = array_column($columnsTopics, 0);

        $data = array_column($result['logs'], "data");

        if(isset($eventName) && $eventName != null){

            $evenSig = new Topics();
            $event = $evenSig->eventSignature($eventName);
            
            $filtered_topics = array_filter($result['logs'], function($log) use($event) {
               
                return in_array($event, $log['topics']);
               
            });

            $filteredData = [];   
            $filtered_data = array_filter($filtered_topics, function($log) use(&$filteredData) {
            
               array_push($filteredData, $log['data']);

            });

            $checkTrxWei = $nftTrxWei->checkTrxWei($filteredData);

        }else{

            $filteredData = []; 
            $filtered_topics = array_filter($result['logs'], function($log) use(&$filteredData) {
               
                 array_push($filteredData, $log['data']);
               
            });

            $checkTrxWei = $nftTrxWei->checkTrxWei($filteredData);

        }

        return $this->result($checkTrxWei);

    }

    /**
     * Method to execute the JSON-RPC request and retrieve the 
     * transaction history
     *
     * @throws Exception If there is an error in the JSON-RPC 
     * response or an empty response is received
     */
    function exec($data){

        # Set up the cURL request
        $ch = curl_init($this->provider);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        

        $json_data = json_encode($data);

        # Send the cURL request with the JSON-RPC request in the body
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($curl);
            print_r($error);
        }

        #print_r($response);

        # error checking
        if (!empty($response)) {
            $decoded_response = json_decode($response, true);
            if (isset($decoded_response['error'])) {
                throw new Exception($decoded_response['error']['message']);
            }
            $result = $decoded_response['result'];
           
        } else {
            throw new Exception("Empty response received.");
        }
        
        # Parse the response to extract the transaction history
        $history = json_decode($response, true)['result'];

        return $history;

    }

    function result($result){

        return $result;

    }
}