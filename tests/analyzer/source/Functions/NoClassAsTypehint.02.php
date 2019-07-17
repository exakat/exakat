<?php 

class X {}
interface I {}

class y {
    function foo3(Closure $x, Countable $i, X ...$x1){}
    function foo4(PDO $x, RecursiveIterator $i, \X ...$x1){}
}
?>