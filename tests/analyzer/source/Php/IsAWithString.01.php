<?php

foo('CC', 'C');

function foo(string $c = 'n', $x) {
    $ee = 'eee';

    is_a('s1', $b);
    
    $a = 'b';
    is_a($a, $b);
    
    is_a(A, $b);
    is_a(\AA, $b);
    is_a(x::A, $b);
    is_a($b === 1 ? 'c' : 'd', $b);
    is_a($e ?: 'e', $b);
    is_a($ee ?? 'f', $b);

    is_a($x);
    is_a($y);
    is_a(new x, $b);
}

const A = 's2';
const AA = 's3';

(new x)->foo('A');
(new x)->foo($d);


class x {
    const A = 's3';
    
    function foo($a) {
        is_a($a);
    }
}



?>