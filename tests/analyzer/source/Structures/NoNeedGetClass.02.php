<?php

use function get_class as bar;

$c  = \get_class($a1->b);
$c::C;

foo(bar($a2->b));
foo(get_class($a3->b));
function foo($b) {
    $b::$p;
}

$d = get_class($d);

?>