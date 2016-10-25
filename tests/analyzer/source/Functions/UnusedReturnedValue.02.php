<?php

function foo($a = 1) {
    if ($a) {
        return ;
    } else {
        return $a;
    }
}

foo();
$a = foo();


class X {
    function fooClass() {
        return 1;
    }
}

trait T {
    function fooTrait() {
        return 1;
    }
}

fooClass();
$a = fooClass();
fooTrait();
$a = fooTrait();


?>