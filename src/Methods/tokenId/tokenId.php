<?php

namespace Nft\History\Methods\tokenId;

class tokenId{

    /**
     * obtain token id based on topics
     * @param array $topics is the array of all topics
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
