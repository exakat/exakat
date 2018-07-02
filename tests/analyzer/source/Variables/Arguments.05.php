<?php

function foo($a, &$b, $c = 2, ...$d) {
    $e = $b + $c + $d + $a;
}
?>