<?php

class x {
    function foo($a, $b) {}
    function foo2($a, $b) {}
    function foo3($a, $c) {}
    function foo4($a, $c) {}
    function foo5($a, $b, $c, $d) {}
}

class y extends x {
    function foo($b, $a) {}
    function foo2($a, $b) {}
    function foo3($d, $c) {}

    function foo5($a, $d, $c, $b) {}
}

?>