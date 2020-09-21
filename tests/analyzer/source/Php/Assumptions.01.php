<?php

function foo() {

    if ($a !== null) { $a->p; }
    if ($b !== null) { $b->m(); }
    if ($c !== null) { $c::$p; }
    if ($d !== null) { $d::method(); }
    if ($e !== null) { $e::C; }
    if ($f !== null) { $f + 1; }

}
?>