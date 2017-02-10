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
    }
}

trait t {
    function clone() {
        return $this;
    }
}

?>