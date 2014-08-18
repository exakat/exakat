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

new x($r2, $w2);
new x2($r2, $w2);
new x3($r3, $w3);

?>