<?php

use A1 as B;

$x = new class {
    function f() {
        A1::f();
    }
};

$b = new class {
    function f() {
        A1::f();
        A2::f();
        \A1::f();
        \A2::f();
        B::f();
        C::f();
    }
};

A1::f();
\A2::f();
