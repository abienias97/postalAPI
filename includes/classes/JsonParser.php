<?php

    require_once 'includes/interfaces/ParserInterface.php';

    class JsonParser implements ParserInterface
    {     
        public function parse($json) {
            $jsonDecoded = json_decode($json);

            return $jsonDecoded;
        }
    }