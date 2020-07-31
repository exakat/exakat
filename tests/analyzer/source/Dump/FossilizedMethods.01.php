<?php

class A {
    function foo0() {}
    function foo1() {}
    function foo2a() {}
    function foo2b() {}
    function foo3a() {}
    function foo3b() {}
    function foo3c() {}
    function foo3d() {}
}

class B1 extends A {
    function foo1() {}
    function foo2a() {}
    function foo2b() {}
    function foo3a() {}
    function foo3b() {}
}

class C1 extends B1 {
    function foo3a() {}
    function foo3b() {}
    function foo3c() {}
}

class D1 extends C1 {
    function foo3c() {}
    function foo3d() {}
}

class B2 extends A {
    function foo2b() {}
    function foo3d() {}
}