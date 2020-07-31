<?php

class A {
    function foo0() {}
    function foo1($a) {}
    function foo2a() {}
    function foo2b() {}
    function foo3a() {}
    function foo3b() {}
    function foo3c() : int {}
    function foo3d($a = 2) {}
}

class B1 extends A {
    function foo1($b) {}
    function foo2a() {}
    function foo2b() {}
    function foo3a() {}
    function foo3b() {}
}

class C1 extends B1 {
    function foo3a() {}
    function foo3b() {}
    function foo3c() : INT {}
}

class D1 extends C1 {
    function foo3c() : InT {}
    function foo3d($a = 3) {}
}

class B2 extends A {
    function foo2b() {}
    function foo3d($a = 4) {}
}