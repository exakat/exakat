<?php

//declare(strict_types=1);

//function foo(string $s = 1) {}
function foo1(?string $s = null) {}
function foo2(?string $s = "1") {}
//function foo3(string $s = 1.1) {}
function foo4(?string $s = 'a'.'b') {}
function foo5(?string $s = <<<STRING

STRING
) {}
function foo6(?string $s = <<<'STRING'

STRING
) {}
//function foo6(string $s = array()) {}
function foo7(?string $s = Null) {}
//function foo8(string $s = True) {}
//function foo9(string $s = False) {}

interface i {
    const STRING = 'a';
    const INTEGER = 1;
    const ARRAY = [3,4];
}
const STRING = 'a', STRING2 = 'b';
const INTEGER = 1;

function foo10(?string $s = STRING) {}
function foo11(?string $s = \STRING) {}
function foo12(?string $s = \INTEGER) {}
function foo13(?string $s = INTEGER) {echo $s;}
function foo14(?string $s = i::INTEGER) { echo $s;}
function foo15(?string $s = i::STRING) {}
function foo16(?string $s = i::ARRAY) {echo $s;}

?>