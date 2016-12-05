<?php

class x {
    function __construct() {
        $a ++;
        $b += 2;
        return $this;
    }

    function __constructor() {
        return null;
    }

    function __destruct() {
        return null;
    }

    function __destructor() {
        return null;
    }
    
    function usableReturn() {
        return true;
    }

    function __bar() {
        return true;
    }

    function __unset($x) {
        $a++;
    }

    function __clone() {
        return $md5;
    }
}

