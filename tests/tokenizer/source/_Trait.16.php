<?php


trait x {
    function __construct(private float $x = 1.0) {}
}
trait x2 {
    function __construct(private int $x = 6) {}
}
trait x3 {
    function __construct(protected ?bool $x = true) {}
}
trait x4 {
    function __construct(public A\D $x) {}
}
trait x5 {
    function __construct(public A\D &$x) {}
}
trait x6 {
    public $a, $b, $c;
    function __construct(public A $x, public V &$x2, ) {}
}

trait x7 {
    function __construct(public A &$x, public V &$x2, public A &$x3, public V &$x4, ) {}
}

?>