<?php


class x {
    function __construct(private float $x = 1.0) {}
}
class x2 {
    function __construct(private int $x = 6) {}
}
class x3 {
    function __construct(protected ?bool $x = true) {}
}
class x4 {
    function __construct(public A\D $x) {}
}
class x5 {
    function __construct(public A\D &$x) {}
}
class x6 {
    public $a, $b, $c;
    function __construct(public A $x, public V &$x2, ) {}
}

class x7 {
    function __construct(public A &$x, public V &$x2, public A &$x3, public V &$x4, ) {}
}

?>