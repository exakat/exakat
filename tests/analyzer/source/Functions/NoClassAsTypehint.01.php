<?php 

class X {}
interface I {}

function foo(X $x, I $i, UNKNOWN $u, string $s){}
function foo2(\X $x, \I $i, \UNKNOWN $u, string $s){}

?>