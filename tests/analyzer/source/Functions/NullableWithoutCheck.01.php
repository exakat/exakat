<?php

function foo(?A $a, ?B $b, C $c, ?D $d, ?E $e) {
    is_null($a);
    
    $b === null;
    
    echo $d;
    
    // $e is not used
}
?>