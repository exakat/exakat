<?php

function foo() {
    $a  =1;
    global $a;
    static $a;
}

function foo2() {
    global $a2;
    static $a2;
    $a2  =1;
}

function foo3() {
    global $a3;
    $a3  =1;
}

function foo4() {
    static $a4;
    global $a4;
}

function foo6() {
    static $a6;
    global $b6;
    $c6 = 1;
}

?>