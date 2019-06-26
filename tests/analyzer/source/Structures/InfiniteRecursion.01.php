<?php

function foo($a, $b) { foo($a, $b); }
function foo2($a, $b) { foo2($b, $a); }
function foo3($a, $b) { foo3($a, $b, $c); }
function foo4($a, $b) { foo4($a); }
function foo5($a, $b) { foo6($a); }

?>