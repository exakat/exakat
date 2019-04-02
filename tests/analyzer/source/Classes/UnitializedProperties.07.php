<?php

class X {
    private $i1 = 1, $i2;
    protected $u1, $u2;
    
    function __construct() {
        $this->i2 = 1 + $this->u2 + $this->v1;
    }
    
    function m() {
        echo $this->i1, $this->i2, $this->u1, $this->u2, $this->v2;
    }
}
?>