<?php

$a->b; // OK

$c->d->e;  // KO

$f->g
  ->h
  ->i; // OK
  
$a->b($c->d); // OK
$a2->b2($c2->d2, $e2->f2); // OK

class x {
    function y() {
        $this->property->method(); // OK
        $this->property->method2()->method3(); // KO

        $this->property->method2()
                       ->method4()
                       ->method5(); // OK
    }
}
?>