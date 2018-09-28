<?php

$a = function () {
    $a = foo();
    echo $a->b;
    
    $b = foo();
    echo $b->b();
    
    $c = foo();
    echo $c::d();
    
    $d = foo();
}

?>