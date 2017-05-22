<?php 

class X {}
interface I {}

interface i {
function foo(X $x, I $i, UNKNOWN $u, string $s); 
function foo2(\X $x, \I $i, \UNKNOWN $u, string $s);
}

trait t {
function foo3(Closure $x, Countable $i){}
function foo4(PDO $x, RecursiveIterator $i){}
}
?>