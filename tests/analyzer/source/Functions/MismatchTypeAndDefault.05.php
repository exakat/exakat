<?php

//declare(strict_types=1);

//function foo(array $s = 1) {}
function foo1(array $s = array()) {}
//function foo2(array $s = "1") {}
//function foo3(array $s = 1.1) {}
//function foo4(array $s = 'a' + 'b') {}
/*
function foo5(array $s = <<<STRING

STRING
) {}
function foo6(array $s = <<<'STRING'

STRING
) {}
*/
//function foo6(array $s = array()) {}
function foo7(array $s = Null) {}
//function foo8(array $s = True) {}
//function foo9(array $s = False) {}

interface i {
    const STRING = 'a';
    const INTEGER = 1;
    const ARRAY = [3,4];
}
const STRING = 'a', STRING2 = 'b';
const INTEGER = 1;

function foo10(array $s = STRING) {}
function foo11(array $s = \STRING) {}
function foo12(array $s = \INTEGER) {}
function foo13(array $s = INTEGER) {echo $s;}
function foo14(array $s = i::INTEGER) { echo $s;}
function foo15(array $s = i::STRING) {}
function foo16(array $s = i::ARRAY) {echo $s;}

?>