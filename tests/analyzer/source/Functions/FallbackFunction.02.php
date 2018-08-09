<?php

namespace {
    function foo() { echo __FUNCTION__.PHP_EOL;}
}

namespace A {
    echo foo();
    echo foo(1234)(5678);
    echo foo3();
}

?>