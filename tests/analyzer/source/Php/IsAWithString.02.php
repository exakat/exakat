<?php

foo('CC', 'C');

function foo(string $c = 'n', $x) {
    $ee = 'eee';

    is_subclass_of('s1', $b, false);
    
    $a = 'b';
    is_subclass_of($a, $b, $b, false);
    
    is_subclass_of(A, $b, false);
    is_subclass_of(\AA, $b, false);
    is_subclass_of(x::A, $b, false);
    is_subclass_of($b === 1 ? 'c' : 'd', $b, $b, false);
    is_subclass_of($e ?: 'e', $b, false);
    is_subclass_of($ee ?? 'f', $b, false);

    is_subclass_of($x, $b, false);
    is_subclass_of($y, $b, false);
    is_subclass_of(new x, $b, false);
    is_subclass_of(new x, $b, true);
}

const A = 's2';
const AA = 's3';

(new x)->foo('A');
(new x)->foo($d);


class x {
    const A = 's3';
    
    function foo($a) {
        is_subclass_of($a, $b, false);
    }
}



?>