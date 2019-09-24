<?php

$a = 'a' . 'a' . "a" . 'a' . 'a' . 'a' . "a" . 'a' . 'a' . 'a' . "a" . 'a' . 'a' . 'a' . "a" . 'a';
class x {const A = 'a';}

$a = 'b' . 'b' . "b" . 'b' . 'b' . 'b' . "b" . 'b' . 'b' . 'b' . "b" . 'b' . 'b' . 'b' . "b" . 'b';
interface f { const B = 'b'; }

$a = 'c' . "c" . <<<H
c
H;

function foo($a = 'c')  {}

$b = 'c';

?>