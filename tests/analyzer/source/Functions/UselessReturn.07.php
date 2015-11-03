<?php

// a trait
trait x {
    function __construct() {
        $a ++;
        $b += 2;
        return true;
    }

    function __destruct() {
        return true;
    }
    
    function usableReturn() {
        return true;
    }
}


?>