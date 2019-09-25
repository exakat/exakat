<?php

$a = function ($a, $b = 1, $c = 3) {
    $a = new A;
    
    $a->foo2($a, $b, $c);
    $a->foo3($a, $b, $c);
};

class A {
    function foo2($a, $b, $c = 3) {}
    function foo3($a, $b = 2, $c = 3) {}
}

?>