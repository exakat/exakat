<?php

class i {
    public const i1 = 2;
    public const i2 = 2;
    public const i3 = 2;
}

function foo($a) {
    $a::i1;
}

function foo1(i $a) {
    $a::i1;
}

function foo2(i $a) {
    $a::i2;
}

function foo2a(i $a) {
    $a::i2;
    $a::i2a;
}

?>