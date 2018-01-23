<?php

define('DEBUG_DEFINE_T', 1);
define('DEBUG_DEFINE_F', 0);
define('DEBUG_DEFINE_V', $v);

function foo() {
    assert(false, 'boolean');
    ++$foo;
}

function foo2() {
    assert(DEBUG_DEFINE_T, 'constant');
    ++$foo2;
}

function foo3() {
    assert(DEBUG_DEFINE_F, 'constant2');
    ++$foo3;
}

function foo4() {
    assert(\DEBUG_DEFINE_T, 'fqn');
    ++$foo4;
}

function foo5() {
    assert(\DEBUG_DEFINE_F, 'fqn2');
    ++$foo5;
}

function foo6() {
    assert($a, 'variable');
    ++$foo6;
}

function foo7() {
    assert(DEBUG_DEFINE_V, 'define');
    ++$foo7;
}


function foo8() {
    assert(debug_define_t, 'constant');
    ++$foo8;
}

function foo9() {
    assert(debug_define_f, 'constant2');
    ++$foo9;
}

function foo10() {
    assert(\debug_define_t, 'fqn');
    ++$foo10;
}

function foo11() {
    assert(\debug_define_f, 'fqn2');
    ++$foo11;
}


?>