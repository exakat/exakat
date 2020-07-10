<?php

function foo($a, $b, $c, $d, $e, $f) {
    bar($c);
    
    $e();
    $o->$d();
    
    array_map($f, $e);
}

function bar(callable $g) {}

?>