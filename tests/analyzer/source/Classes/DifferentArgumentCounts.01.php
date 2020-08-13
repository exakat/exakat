<?php

class x {
    function foo1($a) {}
    function foo1a($a, $b) {}
    function foo2($a, $b) {}
    function foo2a($a, $b) {}
}

new class extends x {
    function foo1($a) {}
    function foo1a($a, $b=  1) {}
    function foo2($a, $b, $c = 1) {}
    function foo2a($a, $b) {}
};

?>