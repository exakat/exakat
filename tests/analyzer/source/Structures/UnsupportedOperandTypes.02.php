<?php

function foo() : array {}
$b = 1 + foo(); 

class b { public array $p; }


function foo2(array $a2) { 
    $o = new b;
    $b = 1 + $o->p; 

    $b = 1 + $a2; 
}

function foo3(array $a3 = array()) { 
    $b = 1 + $a3; 
}

const A = [];
function foo4($a4 = A) { 
    $b = 1 + $a4; 
}

function foo5(array $a5 = A) { 
    $b = 1 + $a5; 
}

$b = $a + 1;

?>