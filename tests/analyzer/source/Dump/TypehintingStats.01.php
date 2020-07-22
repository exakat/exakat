<?php

function foo(string $a, $b, A $c) : foo {}

function (float $a, $b, mixed $c) : void {};

class x {
    function foo(array $a, $b, ?iterable $c) : callable {}
    function __construct(mixed $a, numeric $b, ?\resource $c) {}
}

class mixed3 {}
?>