<?php
use \Everyman\Neo4j\Client;

namespace Loader;

class E_class {
    private $client = null;
    private $file = null;
    private $class = 'Global';
    private $node = null;
    private $global = null;
    private $index = null;
    
    private $getNext = false;
    private $curly_count = 0;
    private $curly_first = 0;
    
    function __construct($client, $file) {
        $this->client = $client;
        $this->file = $file;
    }
    
    public function init($index) {
        $this->class = 'Global';

        $this->node = $this->client
                           ->makeNode()
                           ->setProperty('token', 'E_CLASS')
                           ->setProperty('code', 'Index for class "'.$this->class.'"')
                           ->save();
        $this->global = $this->node;
        $this->index = $index;
        $this->node->relateTo($index, 'INDEX')->save();
        
        return true;
    }
    
    public function relateTo($node) {
        $this->node->relateTo($node, 'CLASS')
              ->setProperty('classname', $this->class)
              ->save();
        
        return true;
    }
    
    function process($token) {
        if ($this->getNext) {
            $this->global = $this->node;
            
            $this->class = $token[1];
            $this->node = $this->client
                               ->makeNode()
                               ->setProperty('token', 'E_CLASS')
                               ->setProperty('code', 'Index for class "'.$this->class.'"')
                               ->setProperty('file', $this->file)
                               ->save();
            $this->node->relateTo($this->index, 'INDEX')->save();
            
            $this->curly_first = $this->curly_count;
            $this->getNext = false;
        } elseif (is_array($token) && token_name($token[0]) == 'T_CLASS') {
            $this->getNext = true;
        } elseif (is_string($token) && $token == '{') {
            $this->curly_count++;
        } elseif (is_string($token) && $token == '}') {
            $this->curly_count--;
            if ($this->curly_count == $this->curly_first) {
                $this->node = $this->global;
            }
        }
    }
}

?>