<?php

function foo($a, $b, $c, $d, $e) {
    foreach($a as $z) {}
    bar($b);
    array_merge(...$c);
    print_r((array) $d);
    strtolower($e);
}

function bar(iterable $c) {}

?>