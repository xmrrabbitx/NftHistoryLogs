<?php

namespace Nft\History\Methods\tokenId;

class tokenId{

    /**
     * obtain token id based on topics
     * @param array $topics is the array of all topics
     */
    function getTokenId($args){


        if(!empty($args)) {
            $topics = $args[0];
        }else{
            throw $this->Exception("empty fields!");
        }

        if(isset($topics[3])){

            # getting topic index 3
            $decodedInputData = $topics[3];

            # extract token ID from input data
            $tokenId = $decodedInputData;
            return $tokenId;
        }
    }

    private function Exception($string)
    {
        return $string;
    }
}
