<?php

const DEBUG_CONST = 33;
const DEBUG_CONST2 = 0;
define('DEBUG_DEFINE', $x);

function foo() {
    assert(false, 'boolean');
    ++$foo;
}

function foo2() {
    assert(DEBUG_CONST, 'constant');
    ++$foo2;
}

function foo3() {
    assert(DEBUG_CONST2, 'constant2');
    ++$foo3;
}

function foo4() {
    assert(\DEBUG_CONST, 'fqn');
    ++$foo4;
}

function foo5() {
    assert(\DEBUG_CONST2, 'fqn2');
    ++$foo5;
}

function foo6() {
    assert($a, 'variable');
    ++$foo6;
}

function foo7() {
    assert(DEBUG_DEFINE, 'define');
    ++$foo7;
}


?>