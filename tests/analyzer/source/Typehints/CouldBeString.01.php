<?php

function foo($a, $b = 'yes', $c, $d, $e, $f) {
    echo $d[$a];
    
    bar($c);
    
    strtolower($f);
}

function bar(string $g) {}

?>