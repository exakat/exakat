<?php

function foo($a, $b, $c, $d) {
    $a . 'b';
    bar($b);
    chr($c);
    strtolower($d);
}

function bar(string $c) {}

?>