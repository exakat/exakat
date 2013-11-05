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
    
    function __construct($client, $file) {
        $this->client = $client;
        $this->file = $file;
        
        $this->files = new E_file($client, $file);
        $this->classes = new E_class($client, $file);
        $this->functions = new E_function($client, $file);
        $this->namespaces = new E_namespace($client, $file);
    }
    
    public function init($index) {
        $this->classes->init($index);
        $this->files->init($index);
        $this->functions->init($index);
        $this->namespaces->init($index);

        return true;
    }
    
    public function relateTo($node) {
        $this->classes->relateTo($node);
        $this->files->relateTo($node);
        $this->functions->relateTo($node);
        $this->namespaces->relateTo($node);

        return true;
    }
    
    function process($token) {
        $this->classes->process($token);
        $this->files->process($token);
        $this->functions->process($token);
        $this->namespaces->process($token);
        
        return true;
    }
}

?>