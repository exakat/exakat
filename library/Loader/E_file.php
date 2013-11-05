<?php
use \Everyman\Neo4j\Client;

namespace Loader;

class E_file {
    private $client = null;
    private $file = null;
    
    function __construct($client, $file) {
        $this->client = $client;
        $this->file = $file;
    }
    
    public function init($index) {
        $this->node = $this->client
                           ->makeNode()
                           ->setProperty('token', 'E_FILE')
                           ->setProperty('code', 'Index for file "'.$this->file.'"')
                           ->save();
        $this->index = $index;
        $this->node->relateTo($index, 'INDEX')->save();
        
        return true;
    }
    
    public function relateTo($node) {
        $this->node->relateTo($node, 'FILE')
             ->setProperty('file', $this->file)
             ->save();
        
        return true;
    }
    
    function process($token) {
        return true;
    }
}

?>