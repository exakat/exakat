<?php

function foo($a, $b, $c1, $c2, $d) {
    $a & true;
    bar($b);
    md5(range(0, 10), $c1);
    count(range(0, 10), $c2);
    strtolower($d);
}

function bar(bool $e) {}

?>