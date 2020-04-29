<?php

class x {
    private $z, $z2;
    protected static $w, $w2;
    
    function __construct() {
        $this->z = 1;
        $this->y = 1;
        self::$w = new A;
    }
    
    function foo() {
        $this->z2 = 2;
        $this->y2 = 2;
        self::$w2 = 2;
    }
}
?>