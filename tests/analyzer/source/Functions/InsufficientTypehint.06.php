<?php

class i {
    private function i1() {}
    private function i2() {}
    private function i3() {}
}

function foo($a) {
    $a->i1();
}

function foo1(i $a) {
    $a->i1();
}

function foo2(i $a) {
    $a->i2();
}

function foo2a(i $a) {
    $a->i2();
    $a->i2a();
}

?>