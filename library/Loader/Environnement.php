<?php
use \Everyman\Neo4j\Client,
    \Loader\Environnement;

namespace Loader;

class Environnement {
    private $client = null;
    private $file = null;

    private $classes = null;
    private $files = null;
    private $functions = null;
    private $namespaces = null;
    
    function __construct($client, $file) {    }
    
    public function init($index) { 
        return true;
    }
    
    public function relateTo($node) {
        return true;
    }
    
    function process($token, $token_name) {
        return true;
    }

    public function initFile() {
        return true;
    }
}

?>