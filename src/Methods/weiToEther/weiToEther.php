<?php

namespace Nft\History\Methods\weiToEther;

class weiToEther{

    /**
     * Method to convert WEI to Ether format
     * @param int $weiValue is the values of WEI in integer format
    */
    function getWeiToEther($args){

        $weiValue = $args[0];
        
        $ether = bcdiv($weiValue, bcpow(10, 18), 2);
 
        return $ether;

    }
}