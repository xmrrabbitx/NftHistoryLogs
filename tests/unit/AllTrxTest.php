<?php

declare(strict_types=1);

namespace Test;

use \PHPUnit\Framework\TestCase;

use Nft\History\Methods\allTrx\allTrx;

final class AllTrxTest extends TestCase{

      /**
     * testHost
     * 
     * @var string
     */
    protected $testProvider = 'https://cloudflare-eth.com';

    /**
     * testContractAddress
     * 
     * @var string
     */
    protected $testContractAddress = "0x7F0159D3A639a035797e92861d9F414246735568";


    /** @test */
    public function testAllTrx(){

        $allTrx = new allTrx($this->testContractAddress, $this->testProvider);

        $result = $allTrx->getAllTrx(["0x0","latest"]);

        $this->assertIsArray($result);
        $this->assertNotNull($result);

    }

}