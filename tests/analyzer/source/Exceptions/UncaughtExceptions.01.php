<?php
class A extends \Exception {}
class B extends A {}
class C extends B {}
class D extends C {}
class F extends C {}
class E {} 

try {
    throw new A();
    throw new B();
    throw new D();
    throw new C();
} 
catch(A $a1) { }
catch(B $b2 ) { }
catch(C $c3 ) { }


?>