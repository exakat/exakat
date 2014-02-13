<?php
use \Everyman\Neo4j\Client;

namespace Loader;

class E_namespace {
    private $client = null;
    private $file = null;
    private $namespace = 'Global';
    private $node = null;
    private $global = null;
    private $namespaces = array();
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
                           ->setProperty('token', 'E_NAMESPACE')
                           ->setProperty('code', 'Index for namespace "'.$this->namespace.'"')
                           ->save();
        $this->global = $this->node;
        $this->index = $index;
        $this->node->relateTo($index, 'INDEX')->save();
        
        return true;
    }
    
    public function relateTo($node) {
        $this->node->relateTo($node, 'NAMESPACE')
              ->setProperty('namespace', $this->class)
              ->save();
        
        return true;
    }
    
    function process($token, $token_name) {
        if ($this->getNext) {
            $this->global = $this->node;
            
            if (is_array($token)) {
                if (isset($this->namespaces[$token[1]])) {
                    $this->class = $token[1];
                    $this->node = $this->namespaces[$token[1]];
                } else {
                    $this->class = $token[1];
                    $this->node = $this->client
                                       ->makeNode()
                                       ->setProperty('token', 'E_NAMESPACE')
                                       ->setProperty('code', 'Index for namespace "'.$this->class.'"')
                                       ->save();
                    $this->node->relateTo($this->index, 'INDEX')->save();
                    $this->namespaces[$token[1]] = $this->node;
                }
            } else {
                $this->class = 'Global';
                $this->node = $this->global;
            }
                
            $this->curly_first = $this->curly_count;
            $this->getNext = false;
        } elseif (is_array($token) && $token_name == 'T_NAMESPACE') {
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