<?php

class x {
    function __get($a) {}
}

    function foo(x $a) {
        $a = $a->a1;
        $a = $a->a2;
        $a = $a->a3;
        $a = $a->a4;
    }
    
    function foo2(x $b) {
        $a = $b->a2;
        $a = $b->a3;
        $a = $b->a4;
    }

    function foo3(x $c) {
        $a = $c->a3;
        $a = $c->a4;
    }

    function foo4(x $d) {
        $a = $d->a4;
    }

?>