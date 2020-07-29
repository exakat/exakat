<?php

class x {
    function foo0() {}
    function foo1a($a) {}
    function foo1b($a) {}
    function foo2a($a, $b) {}
    function foo2b($a, $b) {}
    function foo2c($a, $b) {}
    function foo3a($a, $b, $c) {}
    function foo3b($a, $b, $c) {}
    function foo3c($a, $b, $c) {}
}

class y extends x {
    function foo0() {}

    function foo1a($a) {}
    function foo1b($b) {}

    function foo2a($a, $b) {}
    function foo2b($b, $a) {}
    function foo2c($b, $c) {}
    
    function foo3a($a, $b, $c) {}
    function foo3b($b, $a, $c) {}
    function foo3c($a, $c, $b) {}
}

?>