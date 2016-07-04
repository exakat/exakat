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

    throw new D();
    throw new E();
    throw new F();
} 
catch(A $a1) { }
catch(B $b2 ) { }
catch(C $c3 ) { }


?>