<?php

use A1 as B;

class AA extends AB {}

class A0 extends AA {}

class A1 extends A0 {
    function f() {
        A1::f();
    }
}

class A2 extends A1 {
    function f() {
        A1::f();
        A2::f();
        \A1::f();
        \A2::f();
        B::f();
        C::f();
    }
}

A1::f();
\A2::f();
