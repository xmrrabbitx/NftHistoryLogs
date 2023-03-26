<?php declare(strict_types=1);

namespace Test;

use \PHPUnit\Framework\TestCase;

use Nft\History\Exec\singleThreadExec;


final class ConnectionTest extends TestCase{

    /**
     * testHost
     * 
     * @var string
     */
    protected $testProvider = 'https://mainnet.infura.io/v3/b79cca560563453088d46d6812fdf531';

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

        $exec = new singleThreadExec($this->testContractAddress, $this->testProvider);

        $data = array(
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'net_listening',
            'params' => array(
                
            )
        );

        $result = $exec->singleExec($data);

        $this->assertTrue($result);

    }
}