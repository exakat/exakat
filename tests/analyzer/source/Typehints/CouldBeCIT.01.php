<?php

function foo(A $a, $b = null, $c, $d, $e, $f, $h, $i, $j) {
    echo $a->m();
    
    bar($c);
    
    $e->y = 4;
    
    $f::class;
    $h::constante;
    
    throw $i;
    
    $j instanceof Yes;
}

function bar(B $g) {}

?>