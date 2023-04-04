<?php

namespace Nft\History\Methods\weiToEther;

class weiToEther{

    /**
     * Method to convert WEI to Ether format
     * @param int $weiValue is the values of WEI in integer format
    */
    function getWeiToEther($args){

        $weiValue = $args[0];
        if(preg_match('/^0x[0-9a-fA-F]+$/', $weiValue)){
            
            $weiValueDec = hexdec($weiValue);
            
        }else{
            $weiValueDec = $weiValue;
        }
        $ether = bcdiv($weiValueDec, bcpow(10, 18), 2);
 
        return $ether;

    }
}