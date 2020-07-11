<?php

function foo($a, $b = 3, $c, $d, $e, $f, $g) {
    echo $x[$a];
    
    bar($c);
    
    $e += 4;
    
    pow($f, $g);
}

function bar(int $g) {}

?>