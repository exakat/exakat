<?php

const A = 1;
const B = "1";
const C = true;
const D = false;

class E {
    const F =1;
}

interface T {
    const T = 1.0;
}

$a = $a * A;
$a = $a * B;
$a = $a * C;
$a = $a * D;
$a = $a * E::F;
$a = $a * T::T;

?>