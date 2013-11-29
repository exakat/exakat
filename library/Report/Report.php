<?php

namespace Report;

class Report {
    private $client = null;
    
    function __construct($client) {
        $this->client = $client;
    }
}

?>