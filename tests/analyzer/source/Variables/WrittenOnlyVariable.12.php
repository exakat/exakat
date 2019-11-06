<?php 

function foo() {
    foreach($array as $a => $b)  {
        $e[] = compact('a', 'b');
    }

    $c = 1;
    compact('c');
    $d = 2;
    $d = 2;
}