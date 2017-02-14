<?php

// Not in a class
    function __construct() {
    }

    function __destruct() {
    }
    
    function usableReturn() {
    }

class x {
    function __construct($inX) {
        return false;
    }
}

trait t {
    function __clone() {
        return $this;
    }
}

?>