<?php

class x {
    function __constructor() {
        $a ++;
        $b += 2;
        return true;
    }

    function __destructor() {
        return true;
    }
    
    function usableReturn() {
        return true;
    }
}


?>