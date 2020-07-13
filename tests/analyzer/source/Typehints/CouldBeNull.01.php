<?php

function foo($a, $b = null, $c, $d, $e, $f, $g) {
    echo $a ?? 2;
    
    bar($c);
    
    $e === null;
    
    file_get_contents('', true, $f);
}

function bar(?int $g) {}

?>