<?php

function foo($b) {
    $a = empty($b);
    $e = empty($b);

    $a = isset($b);
    $e = isset($b);

    $a = $b++;
    $e = $b++;

    $a = -$b;
    $e = -$b;
    
    $a = $b ?? 'c';
    $e = $b ?? 'c';

    $a = new A();
    $e = new A();
}

?>