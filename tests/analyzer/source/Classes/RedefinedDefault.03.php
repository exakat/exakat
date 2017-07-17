<?php

class foo {
    private $redefined = 'd';
    private $unRedefined = 2;
    
    function __construct() {
        $this->redefined   = (strpos(PHP_SAPI, 'c') !== false ? 'a' : 'b');
        $this->unRedefined = (strpos($_GET['post'], 'c') !== false ? 'a' : 'b');
    }
}
?>