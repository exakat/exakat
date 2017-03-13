<?php

function foo() {}

interface foo {}

function bar() {}

interface bar2 {}

// just check
$a = new class { function foo($c) {} };
$f =  function ($x) { return $x; };

class c { function foo($i) {}}
trait t { function foo($t) {} }

?>