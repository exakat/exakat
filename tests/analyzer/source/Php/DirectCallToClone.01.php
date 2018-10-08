<?php

class Foo {
    function __clone() {}
}

$a = new Foo;
$a->__clone();

$a = new Foo;
$a->__CLONE();

F::__clone();

__clone();


?>