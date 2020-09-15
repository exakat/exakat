<?php

class x {
    function __construct() {}
    static function foo($a) : void{}
}

$a = new x();

$b = array('X', 'foo')();
$c = "X::foo"();

array('X', 'foo')(1);
"X::foo"(1);
?>