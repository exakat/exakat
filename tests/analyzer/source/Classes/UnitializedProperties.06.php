<?php

class X {
    private $i1 = 1, $i2 = 2;
    protected $u1, $u2;
    
    function m() {
        echo $this->i1, $this->i2, $this->u1, $this->u2;
    }
}
?>