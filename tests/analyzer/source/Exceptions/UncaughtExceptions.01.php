<?php
class A extends \Exception {}
class B extends A {}
class C extends B {}
class D extends C {}
class F extends C {}
class E {} 
class G extends \Exception {}

try {
    throw new A();
    throw new B();
    throw new C();
    throw new D();
    throw new G();
} 
catch(A $a1) { }
catch(B $b2 ) { }
catch(C $c3 ) { }


?>