<?php


namespace A {
    class E {};
    class C {};
    class A {};
    // B is not defined. 
}


namespace B {

use A\A, B;
use A\{D, E};

use A\{ C,
    const B,
    function D };
//B, 

new A();
new B();
new C();
new D();
new E();
new \A\A();
new A\A();
}
