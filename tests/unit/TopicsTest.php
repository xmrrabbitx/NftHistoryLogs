<?php

namespace Test\Unit;

use \PHPUnit\Framework\TestCase;

use Nft\History\nftHistory;
use Nft\History\Methods\Transfer;
use kornrunner\Keccak;


final class TopicsTest extends TestCase{

    
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
    public function eSig(){

        $nfthistory = new nftHistory($this->testContractAddress, $this->testHost);

        $keccTransfer = Keccak::hash("Transfer(address,address,uint256)",256);
        $this->assertSame(("0x" . $keccTransfer),  $nfthistory->eventSig("Transfer"));

        $keccApprovalForAll = Keccak::hash("ApprovalForAll(address,address,bool)",256);
        $this->assertSame(("0x" . $keccApprovalForAll), $nfthistory->eventSig("ApprovalForAll"));

        $keccApproval = Keccak::hash("Approval(address,address,uint256)",256);
        $this->assertSame(("0x" . $keccApproval),  $nfthistory->eventSig("Approval"));

        }


}