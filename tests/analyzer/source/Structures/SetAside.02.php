<?php

class x {
    private $a = 1;
    private $a2 = 1;
    private $a3 = 1;

    function foo() {
        // strict minimum
        $b = $this->a; 
        $this->a = 3; 
        $this->a = $b;
    }
    
    function foo2() {
        // $a <-> $b is broken
        $b = $this->a2; 
        $this->a2 = 3; 
        $this->a2 = $c;
    }
    
    function foo3() {
        // $a is not reassigned
        $b = $this->a3; 
        $this->a3 = $b;
    }
}
?>