<?php

class x {
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