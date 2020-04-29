<?php

class x {
    private $x = 1, $x2 = 3, $y = null, $y2 = null, $z, $z2;
    protected A $w, $w2;
    
    function __construct() {
        $this->z = 1;
        $this->y = 1;
        $this->w = new A;
    }
    
    function foo() {
        $this->x2 = 2;
        $this->z2 = 2;
        $this->y2 = 2;
        $this->w2 = 2;
    }
}
?>