<?php

declare(strict_types=1);

namespace Test;

use \PHPUnit\Framework\TestCase;

use Nft\History\nftHistory;
use Nft\History\Methods\Transfer;

final class AllTrxTest extends TestCase{

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


    /** @test */
    public function testAllTrx(){

        $nfthistory = new nftHistory($this->testContractAddress, $this->testHost);

        $result = $nfthistory->allTrx("0x0","latest");

        $this->assertIsArray($result);
        $this->assertNotNull($result);

    }

}