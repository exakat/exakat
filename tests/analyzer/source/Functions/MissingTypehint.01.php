<?php

function foo($a) : void {}
function foo2(string  $a) : void {}
function foo3(C  $a) : void {}

function bar1(string $a) {}
function bar2(string $a) : void {}
function bar3(string $a) : C {}
function (string $a) {};

class x {
    function __construct() {}
    function __get($a2) {}
    function foo_method() {}
}

class x2 {
    function __construct($c) {}
    function __get($d) {}
    function foo_method($b) {}
}

?>