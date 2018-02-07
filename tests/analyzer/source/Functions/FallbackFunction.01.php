<?php

namespace {
    function foo() { echo __FUNCTION__.PHP_EOL;}
    function foo2() { echo __FUNCTION__.PHP_EOL;}
}

namespace A {
    function foo2() { echo __FUNCTION__.PHP_EOL;}
    function foo3() { echo __FUNCTION__.PHP_EOL;}
    echo foo();
    echo foo2();
    echo foo3();
}



?>