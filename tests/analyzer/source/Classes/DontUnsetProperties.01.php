<?php

class A {
    public $a, $b;
    
    function displayAinA(){ 
        unset( $this->a);
        (unset) $this->b;

        unset($this->a['d']);
        unset($b->a);
    }
}

$a = new A();
var_dump($a->a);
$a->b = null;
var_dump(isset($a->b));
$a->a;
$a->b;

?>
