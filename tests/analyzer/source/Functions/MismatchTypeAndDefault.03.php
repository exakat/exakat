<?php

//declare(strict_types=1);

function foo(int $s = 1) {}
//function foo2(int $s = "1") {}
//function foo3(int $s = 1.1) {}
function foo4(int $s = 'a' + 'b') {}
/*
function foo5(int $s = <<<STRING

STRING
) {}
function foo6(int $s = <<<'STRING'

STRING
) {}
*/
//function foo6(int $s = array()) {}
function foo7(int $s = Null) {}
//function foo8(int $s = True) {}
//function foo9(int $s = False) {}

interface i {
    const STRING = 'a';
    const INTEGER = 1;
    const ARRAY = [3,4];
}
const STRING = 'a', STRING2 = 'b';
const INTEGER = 1;

function foo10(int $s = STRING) {}
function foo11(int $s = \STRING) {}
function foo12(int $s = \INTEGER) {}
function foo13(int $s = INTEGER) {echo $s;}
function foo14(int $s = i::INTEGER) { echo $s;}
function foo15(int $s = i::STRING) {}
function foo16(int $s = i::ARRAY) {echo $s;}

?>