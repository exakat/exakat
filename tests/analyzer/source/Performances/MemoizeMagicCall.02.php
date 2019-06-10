<?php

class x {
    function __get($a) {}
}

    
    function foo(x $b) {
        $a = $b->a1;
    }
    
    function foo2(x $b) {
        $a = $b->a2;
        $a = $b->a2;
    }

    function foo2a($b) {
        $a = $b->a2;
        $a = $b->a2;
    }
    
    function foo3(x $b) {
        $a = $b->a3;
        $a = $b->a3;
        $a = $b->a3;
    }
    
    function foo4(x $b) {
        $a = $b->a4;
        $a = $b->a4;
        $a = $b->a4;
        $a = $b->a4;
    }

    function foo_rw(x $b) {
        $a = $b->b3;
        $b->b3 = $a + 1; // written 
    }

    // 2 access, but in split context
    function foo_split1(x $b) {
        $a = $b->a5;
    }
    function foo_split2(x $b) {
        $a = $b->a5;
    }

?>