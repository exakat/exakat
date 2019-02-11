<?php

interface i extends i2 {
    function i1() ;
    function i2() ;
    function i3() ;
}

interface i2 {
    function i22() ;
}

function foo($a) {
    $a->i1();
}

function foo1(i $a) {
    $a->i1();
}

function foo2(i $a) {
    $a->i22();
}

function foo2a(i $a) {
    $a->i2();
    $a->i22();
    $a->i2a();
}

?>