<?php

class i {
    const STRING = 'a';
    const INTEGER = 1;
    const ARRAY = [3,4];
}
const STRING = 'a', STRING2 = 'b';
const INTEGER = 1;

function foo10(string $s = STRING) {}
function foo11(string $s = \STRING) {}
function foo12(string $s = \INTEGER) {}
function foo13(string $s = INTEGER) {echo $s;}
function foo14(string $s = i::INTEGER) { echo $s;}
function foo15(string $s = i::STRING) {}
function foo16(string $s = i::ARRAY) {echo $s;}
function foo17(string $s = 3 . 3) {echo $s;}

?>