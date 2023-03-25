<?php

namespace Nft\History\Methods\weiToEther;

class weiToEther{

    function getWeiToEther($args){

        $weiValue = $args[0];
        
        $ether = bcdiv($weiValue, bcpow(10, 18), 2);
 
        return $ether;

    }
}