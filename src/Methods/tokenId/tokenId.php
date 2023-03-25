<?php

namespace Nft\History\Methods\tokenId;

class tokenId{

    /**
     * @param array $topics topics of a contract address
     */
    function getTokenId($args){

        $topics = $args[0];

        # getting topic index 3
        $decodedInputData = $topics[3];

        # extract token ID from input data
        $tokenId = hexdec($decodedInputData);

        return $tokenId;

    }
}
