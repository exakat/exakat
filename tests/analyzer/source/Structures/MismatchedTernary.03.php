<?php

class x {
    const I = ' ';
}

$a ? ' ' : x::I;
$b ? 2 : x::I;
$b ? 4 : ($x = x::I);
$b ? 4 : 1 + 5;

?>