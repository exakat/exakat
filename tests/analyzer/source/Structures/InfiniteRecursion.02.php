<?php

function foo1($a, $b) { if ($a) { ++$c;} ++$a; foo1($a, $b); }
function foo2($a, $b) { if ($a) { ++$c;} ++$b; foo2($a, $b); }
function foo3($a, $b) { if ($a) { ++$c;} ++$a; ++$b; foo3($a, $b); }
function foo4($a, $b) { if ($a) { ++$c;} echo $a + $b; foo4($a, $b); }

?>