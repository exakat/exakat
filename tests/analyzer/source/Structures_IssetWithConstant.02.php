<?php

const X = 1;

class X {
    function f() {
        if (isset(X)) {}
        if (isset(Y::X)) {}
        if (isset(Y::$x[$b])) {}
    }
}

$x = new X;
$x->f();