<?php
class A extends \Exception {}
class B extends A {}
class C extends B {}

class C1 extends \Exception {}
class D extends C1 {}
class F extends C1 {}
class E {} 

try {
    throw new A();
    throw new B();
    throw new C();

    throw new A::$E;
    throw new $c->d[2];
    throw new $a->b;
} 
catch(A $a1) { }


?>