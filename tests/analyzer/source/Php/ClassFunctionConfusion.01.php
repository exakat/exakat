<?php

function foo() {}

class foo {}

function bar() {}

class bar2 {}

// just check
$a = new class { function foo($c) {} };
$f =  function ($x) { return $x; };

interface i { function foo($i) ;}
trait t { function foo($t) {} }

?>