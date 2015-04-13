<?php

class x {
    function __construct($read1, &$written1) {}
}

class x2 {
    function x2($read2a, &$written2a) {}
}

class x3 {
    function __construct($read3a, &$written3a) {}
    function x3($read3b, &$written3b) {}
}

new x($r1->a, $w1->b);
new x2($r2->a, $w2->b);
new x3($r3->a, $w3->b);
?>