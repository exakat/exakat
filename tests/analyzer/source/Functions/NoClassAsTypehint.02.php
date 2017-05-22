<?php 

class X {}
interface I {}

class y {
    function foo3(Closure $x, Countable $i){}
    function foo4(PDO $x, RecursiveIterator $i){}
}
?>