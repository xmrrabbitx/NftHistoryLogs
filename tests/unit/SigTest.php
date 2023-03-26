<?php

namespace Test\Unit;

use \PHPUnit\Framework\TestCase;

use Nft\History\Methods\eventSig\eventSig;
use kornrunner\Keccak;


final class SigTest extends TestCase{

    
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

        $eventSig = new eventSig($this->testContractAddress, $this->testHost);

        $keccTransfer = Keccak::hash("Transfer(address,address,uint256)",256);
        $this->assertSame(("0x" . $keccTransfer),  $eventSig->getEventSig(["Transfer"]));

        $keccApprovalForAll = Keccak::hash("ApprovalForAll(address,address,bool)",256);
        $this->assertSame(("0x" . $keccApprovalForAll), $eventSig->getEventSig(["ApprovalForAll"]));

        $keccApproval = Keccak::hash("Approval(address,address,uint256)",256);
        $this->assertSame(("0x" . $keccApproval),  $eventSig->getEventSig(["Approval"]));

        }


}