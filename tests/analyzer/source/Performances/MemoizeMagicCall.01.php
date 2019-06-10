<?php

class x {
    function __get($a) {}
    
    function foo() {
        $a = $this->a1;
    }
    
    function foo2() {
        $a = $this->a2;
        $a = $this->a2;
    }
    
    function foo3() {
        $a = $this->a3;
        $a = $this->a3;
        $a = $this->a3;
    }
    
    function foo4() {
        $a = $this->a4;
        $a = $this->a4;
        $a = $this->a4;
        $a = $this->a4;
    }

    function foo_rw() {
        $a = $this->b3;
        $this->b3 = $a + 1; // written 
    }

    // 2 access, but in split context
    function foo_split1() {
        $a = $this->a5;
    }
    function foo_split2() {
        $a = $this->a5;
    }
}

?>