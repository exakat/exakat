<?php
class A extends \Exception {}
class B extends A {}
class C extends B {}

class C1 extends \Exception {}
class D extends C1 {}
class F extends C1 {}
class E {} 

function goo(Exception $b) {
     throw $b;
}

try {
    $a = new C1();
    throw $a;

    goo(new B());

    throw rand(0, 1) ?  new D() : new E();
    throw new F();
} 
catch(A $a1) { }
catch(B $b2 ) { }
catch(C $c3 ) { }


?>