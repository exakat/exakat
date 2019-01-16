<?php
class A extends \Exception {}
class B extends A {}
class C extends B {}
class C2 extends B {}

class C1 extends \Exception {}
class D extends C1 {}
class F extends C1 {}
class E {} 

class G extends \Exception {}

try {
    throw new A();
    throw new B();
    throw new C();
    throw new C2();
    throw new G();

    throw new A::$E;
    throw new $c->d[2];
    throw new $a->b;
} 
catch(A $a1) { }


?>