<?php

class X {
    const A1 = '\X';
    const A2 = '\x';
    const A3 = '\a';
    const A4 = 'x';

}

$a = x::A1;
$b = x::A2;
$c = x::A3;
$d = x::A4;

?>