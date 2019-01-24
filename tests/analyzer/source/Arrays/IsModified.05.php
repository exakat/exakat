<?php
function foo($phpversion) {
        // version range 1.2.3-4.5.6
        foo2($x['a'], $y['a']);
        list($lower['a'], A::$upper['a']) = explode('-', $phpversion);
}

function foo2(&$a, $b) {
    $a = 1; $b = 3;
}


?>