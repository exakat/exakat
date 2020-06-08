<?php

function foo($a) : void {}
function foo2(string  $a1) : void {}
function foo3(C  $a2) : void {}

function bar1(string $a3) {}
function bar2(string $a4) : void {}
function bar3(string $a5) : C {}
function (string $a6) {};

class x {
    function __construct() {}
    function __get($a7) {}
    function foo_method() {}
}

class x2 {
    function __construct($c) {}
    function __get($d) {}
    function foo_method($b) {}
}

?>