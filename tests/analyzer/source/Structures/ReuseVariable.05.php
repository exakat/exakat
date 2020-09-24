<?php

function foo($b) {
    $a = empty($b);

    $a = isset($b);
    if ( isset($b) && (empty($b))) {
        echo $b ?? 'c';
    }

    $a = $b++;
    echo $b++;

    $a = -$b;
    echo -$b;
    
    $a = $b ?? 'c';

    $a = new A();
    (new A())->foo();
}

?>