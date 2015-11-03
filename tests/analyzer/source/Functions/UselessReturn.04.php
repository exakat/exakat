<?php

class x {
    function __constructor() {
        $a ++;
        $b += 2;
        return;
    }

    function __destructor() {
        return null;
    }
    
    function usableReturn() {
        return true;
    }

    function __unset($x) {
        $a++;
    }

    function __clone() {
        return $md5;
    }
}

?>