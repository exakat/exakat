<?php

function foo() {}
class x {
    function method() { }
    static function Staticmethod() { }
}

foo();
FOO();
\FOo();
foO();
\foo();

$a = function ($x) {};
$a();

$b = new x;
$b->method();
$b->METHOD();
$b->$c();
$b::Staticmethod();
$b::StaticMETHOD();
$b::$c();

x::Staticmethod();
x::StaticMETHOD();

?>