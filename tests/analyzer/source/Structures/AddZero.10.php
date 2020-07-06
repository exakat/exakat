<?php

const A = 0;
const B = true;

$a = $a + A;
$a = $a - A;

$a = $a + B;
$a = $a - B;

$a = $a + i::C;
$a = $a - i::C;

interface i {
    const C = 0;
}

?>