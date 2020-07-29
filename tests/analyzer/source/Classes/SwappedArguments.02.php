<?php

class x {
    function foo($a, $b = 1) {}
    function foo2($a, $b) {}
    function foo3($a, $c) {}
    function foo4($a, $c) {}
    function foo5($a, $b, $c = 5, $d = 6) {}
}

class y extends x {
    function foo($b, $a = 2) {}
    function foo2($a, $b) {}
    function foo3($d, $c) {}

    function foo5($a, $d, $c = 3, $b = 4) {}
}

?>