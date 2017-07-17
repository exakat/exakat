<?php

class bar {
    function foo($a, $b = 1, $c = 3) {
        foo2($a, $b, $c);
        foo3($a, $b, $c);
    }
}

function foo2($a, $b, $c) {}
function foo3($a, $b = 2, $c = 3) {}

?>