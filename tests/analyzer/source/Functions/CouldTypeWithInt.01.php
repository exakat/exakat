<?php

function foo($a, $b, $c, $d) {
    $a + 1;
    bar($b);
    chr($c);
    strtolower($d);
}

function bar(int $c) {}

?>