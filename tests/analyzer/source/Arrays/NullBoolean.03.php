<?php

class x {
    private $p = null;
    protected $p2 = "string"; 
    public $p3 = "string2"; 
    
    function bar() {
        $this->p2 = null;
        $this->p3 = 'null';
    }
    
    function foo() {
        echo $this->p[1] + $this->p2[2] + $this->p3[3];
    }
}
?>