<?php

function foo(&$a) {
    $a[3] = 3;
}

$x = new stdclass;

function &bar() {
    $id = 1;
    return $d;
}

function bar2() {
    $id = 1;
    return $d;
}

$x = new stdclass;
print_r($_ENV);
foo($_SERVER);
foo(new x);
//foo(clone $x);
foo(bar());
foo(bar2());
foo(bar3());
print_r($_SERVER);

class x {
    function bar() {
        foo($this);
//        foo(33);
    }
}

?>
