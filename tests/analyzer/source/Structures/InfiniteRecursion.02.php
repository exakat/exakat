<?php

function foo1($a, $b) { ++$a; foo1($a, $b); }
function foo2($a, $b) { ++$b; foo2($a, $b); }
function foo3($a, $b) { ++$a; ++$b; foo3($a, $b); }
function foo4($a, $b) { echo $a + $b; foo4($a, $b); }

?>