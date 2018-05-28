<?php

//declare(strict_types=1);

//function foo(bool $s = 1) {}
//function foo2(bool $s = "1") {}
//function foo3(bool $s = 1.1) {}
//function foo4(bool $s = 'a' + 'b') {}
/*
function foo5(bool $s = <<<STRING

STRING
) {}
function foo6(bool $s = <<<'STRING'

STRING
) {}
*/
//function foo6(bool $s = array()) {}
function foo7(bool $s = Null) {}
function foo8(bool $s = True) {}
function foo9(bool $s = False) {}

interface i {
    const STRING = 'a';
    const INTEGER = 1;
    const ARRAY = [3,4];
}
const STRING = 'a', STRING2 = 'b';
const INTEGER = 1;

function foo10(bool $s = STRING) {}
function foo11(bool $s = \STRING) {}
function foo12(bool $s = \INTEGER) {}
function foo13(bool $s = INTEGER) {echo $s;}
function foo14(bool $s = i::INTEGER) { echo $s;}
function foo15(bool $s = i::STRING) {}
function foo16(bool $s = i::ARRAY) {echo $s;}

?>