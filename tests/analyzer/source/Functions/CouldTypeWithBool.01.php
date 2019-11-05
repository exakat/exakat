<?php

function foo($a, $b, $c, $d) {
    $a & true;
    bar($b);
    count(range(0, 10), $c);
    strtolower($d);
}

function bar(bool $c) {}

?>