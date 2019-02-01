<?php

const C = 'c';
const E = array();


foo();
foo2();
foo3();
foo4();
foo5();

function foo() {
    $a = '';
    $a[] = 2;
}

function foo2() {
    $b = ''."b";
    $b[3] = 2;
}

function foo3() {
    $c = <<<HERE
    
    
HERE;
    $c["d"] = 2;
}

function foo4() {
    $d = 4;
    $c["d"] = 2;
}

function foo5() {
    $d = C;
    $d["d"] = 2;

    $e = E;
    $e["d"] = 2;
    var_dump($e);
}

function foo6() {
    $f = array();
    $f[] = 2;
}

?>