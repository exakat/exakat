<?php

$a = function () {
    $a = foo();
    echo $a->b;
    
    $b = foo();
    echo $b->b();

    $b2 = foo();
    isset($b2);

    $b3 = foo();
    empty($b3);

    list($b4) = foo();
    empty($b4);

    $b5 = foo();
    print $b5;

    $b6 = foo();
    ?>A<?= $b6;
    
    $c = foo();
    echo $c::d();
    
    $d = foo();
}

?>