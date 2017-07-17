<?php

class bar {
    function foo($a, B $b, C $c) {
        foo2($a, $b, $c);
        foo3($a, $b, $c);
    }
}

function foo2($a, $b, $c) {}
function foo3($a, BB $b, C $c) {}

?>