<?php

function foo($a, $b)  { if ($a) { ++$c;} foo($a, $b); }
function foo2($a, $b) { if ($a) { ++$c;} foo2($b, $a); }
function foo3($a, $b) { if ($a) { ++$c;} foo3($a, $b, $c); }
function foo4($a, $b) { if ($a) { ++$c;} foo4($a); }
function foo5($a, $b) { if ($a) { ++$c;} foo6($a); }
function foo6() { if ($a) { foo6();} }
function foo7($a, $b) { if ($a) { $foo8();} }

?>