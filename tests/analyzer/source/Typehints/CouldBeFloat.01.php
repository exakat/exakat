<?php

function foo($a, $b = 3.1, $c, $d, $e, $f, $g) {
    bar($c);
    
    $e += 4.3;
    
    pow($f, $g);
}

function bar(float $g) {}

?>