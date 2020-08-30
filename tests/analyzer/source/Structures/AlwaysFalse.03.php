<?php

function fooA(A $a) { if ($a instanceof B) {} }
function fooB(A $a) { if ($a instanceof A) {} }

function fooC() : C {}
$a = fooC(); 
if ($a instanceof C) {}
if ($a instanceof D) {}

?>