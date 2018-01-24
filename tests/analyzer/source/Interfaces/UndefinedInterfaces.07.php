<?php

class A implements i {
    const b = 'i';
    
    public $b = 'i';
}

interface i {}


$a instanceof bc;
$a instanceof i;

$a instanceof $b;

$c instanceof $a->b;

$c = new A;
var_dump($c instanceof A::$b);

?>