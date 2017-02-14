<?php

class x {
    function __construct() {
        $this->x = function() { return 1; };
    }

    function __destruct() {
        $this->x = function() { return 2; };
    }
    
    function usableReturn() {
        $this->x = function() { return 3; };
    }

    function __clone() {
        return $this;
    }
}


?>