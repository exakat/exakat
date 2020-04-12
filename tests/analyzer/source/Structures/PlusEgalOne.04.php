<?php

$a = 1 + 3; 
$a = $a + 1;

$a = $a + A::c1;
$a = $a + A::c2;

$a = C1 + $a;
$a = C2 + $a;

const C1 = 1;
const C2 = 2;

class A {
    const c1 = 1;
    const c2 = 2;
}

?>