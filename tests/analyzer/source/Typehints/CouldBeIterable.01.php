<?php

function foo($a, $c, $d, $e, $f, $g) {
    echo $a[$x];
    
    bar($c);
    
    foreach($c as $w) {}
    
    yield from $g;
    
    is_iterable($f);
}

function bar(iterable $g) {}


?>