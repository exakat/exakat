<?php

class x {
    function __construct() {
        $a ++;
        $b += 2;
        return $this;
    }

    // Destructor is on purpose
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

class y {
    function __construct($y) {
        $a ++;
        $b += 2;
        return ;
    }
}

class z {
    function __construct($z) {
        $a ++;
        $b += 2;
        return null;
    }
}
?>