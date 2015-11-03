<?php

const X = 1;

class X {
    function f() {
        if (isset(X[$a])) {}
        if (isset(Y::X[$b])) {}
        if (isset(Y::$x[$b])) {}
    }
}

$x = new X;
$x->f();