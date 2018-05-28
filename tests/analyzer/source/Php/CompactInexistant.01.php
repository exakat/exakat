<?php

function foo() {
    $a = 1;
    $b = 2;
    var_dump(compact('a', 'b'));
    var_dump(compact('c'));
}

class x {
    function foo2($b2) {
        $a2 = 1;
        var_dump(compact('c2'));
        var_dump(compact('a2', 'b2'));
    }
}

$a = function ($b3) use ($d3) {
        $a3 = 1;
        var_dump(compact('c3'));
        var_dump(compact('a3', 'b3', 'd3'));
    }

?>