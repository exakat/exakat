<?php

interface A {
    function bar();
}

class AB implements A {
    protected $pab = 1;
    function bar() {}
}

class AB2 implements A {
    protected $pab2 = 1;

    function bar() {}
}

function foo(A $a, AB $b) {
    $a->pab = 1;
    $a->pab2 = 1;

    $b->pab = 1;
    $b->pab2 = 1;

    $b->cou;
}


?>