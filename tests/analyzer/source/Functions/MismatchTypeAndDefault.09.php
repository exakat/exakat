<?php
foo(); 

function foo(string $a = \Exception::class) { 
    var_dump($a); 
}

const A = 'a';

function foo2(string $a = A * 2) { 
    var_dump($a); 
}
foo2();
?>