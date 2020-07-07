<?php

function foo($a, $b = array(), $c, $d, $e, $f) {
    echo $a[3];
    
    bar($c);
    
    $e[] = 4;
    
    array_diff($f);
}

function bar(array $g) {}

?>