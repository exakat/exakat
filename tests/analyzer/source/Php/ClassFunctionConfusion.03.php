<?php

function foo() {}

trait foo {}

function bar() {}

trait bar2 {}

// just check
$a = new class { function foo($c) {} };
$f =  function ($x) { return $x; };

interface i { function foo($i) ;}
class c { function foo($t) {} }

?>