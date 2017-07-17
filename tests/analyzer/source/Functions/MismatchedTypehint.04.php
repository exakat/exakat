<?php

$a = function ($a, B $b, C $c) {
    A::foo2($a, $b, $c);
    A::foo3($a, $b, $c);
};

class A {
    static function foo2($a, $b, $c) {}
    static function foo3($a, BB $b, C $c) {}
}

?>