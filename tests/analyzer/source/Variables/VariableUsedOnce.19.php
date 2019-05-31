<?php

$a = 1;

$b = compact('a', 'b', 'c');

extract($b);

function ($df, &$rf, ...$vf) use ($ef) {
    global $gf;
    static $sf;
    
    $af = 1;
    
    $bf = compact('af', 'bf', 'cf', 'df', 'ef', 'gf', 'sf', 'vf', 'rf', $x);
    
    $df = 3;
};

?>