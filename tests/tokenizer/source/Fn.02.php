<?php

function matchInArrowFunction($x) {
    yield $c => fn($x) => match(true) {
        1, 2, 3, 4, 5 => 'foo',
        default => ['bar' => 1],
    };
    
    return $fn;
}

$fn = fn($x) => 10 * $x;

?>