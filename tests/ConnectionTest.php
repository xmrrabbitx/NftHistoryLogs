<?php declare(strict_types=1);

namespace Test;

use \PHPUnit\Framework\TestCase;

use Nft\History\nftHistory;
use Nft\History\Methods\Transfer;


final class ConnectionTest extends TestCase{

    /**
     * testHost
     * 
     * @var string
     */
    protected $testHost = 'https://cloudflare-eth.com';

    /**
     * testContractAddress
     * 
     * @var string
     */
    protected $testContractAddress = "0x7F0159D3A639a035797e92861d9F414246735568";

    /**
     * @var object nftHistory is a curl request to a decenterlised provider
     */
    /** @test */
    public function testConnection():void{

        $nfthistory = new nftHistory($this->testContractAddress, $this->testHost);

        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'net_listening',
            'params' => array(
                
            )
        );

        $result = $nfthistory->exec($data);

        $this->assertTrue($result);

    }
}