<?php

$a = function ($a, $b = 1, $c = 3) {
    A::foo2($a, $b, $c);
    A::foo3($a, $b, $c);
};

class A {
    static function foo2($a, $b, $c = 3) {}
    static function foo3($a, $b = 2, $c = 3) {}
}

?>