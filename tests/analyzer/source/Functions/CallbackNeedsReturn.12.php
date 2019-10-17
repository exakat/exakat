<?php

array_map('foo', array());
array_map('foo2', array());

$a = function () : void { };
$a2 = function () : int { return 2;};

array_map($a, array());
array_map($a2, array());

function foo() : void {}
function foo2() : int { return 2;}
?>