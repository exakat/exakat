<?php

function foo($a, $b = true, $c, $d, $e, $f) {
    echo $a[3];
    
    bar($c);
    
    $e[] = 4;
    
    clearstatcache($f);
}

function bar(bool $g) {}

?>