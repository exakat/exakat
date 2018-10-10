<?php

interface i3 {
    const I3 = 7;
}

interface i4 {
    const I4 = 8;
}

interface i2 {
    const I2 = 6;
}

interface i1 extends i2 {
    const I1 = 5;
}

class D implements i3 {
    const D = 2;
}

class C extends D implements i4 {
    const C = 2;
}

class B extends C {
    const B = 2;
}

class A extends B implements i1 {
    const A = 1;
    
    function foo() {
        A::A;
        A::B;
        A::C;
        A::D;
        A::I1;
        A::I2;
        A::I3;
        A::I4;
    }
}
?>