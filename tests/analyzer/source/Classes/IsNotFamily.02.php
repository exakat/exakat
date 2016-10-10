<?php

//use A1 as B;

class A1 {
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
        \C::f();
    }
}

A1::f();
\A2::f();

trait C {
    function cf() {
        A1::f();
        A2::f();
        \A1::f();
        \A2::f();
        B::f();
        C::f();
    }
}

interface i {
    function if() ;
}