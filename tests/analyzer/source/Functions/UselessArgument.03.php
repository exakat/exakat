<?php

class x {
    static function foo($a, $b) {}
    static function foo2($a, $b) {}
}

X::foo(1, 2);
X::foo(2, 2);
X::foo(3, 2);

X::foo2(1, 2);
X::foo2(2, 2);
X::foo2(3, 3);
