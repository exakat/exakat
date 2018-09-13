<?php

class A extends \ArrayObject {
    function foo() { $this['a'] = 1;}
}

class B extends A {
    function foo() { $this['b'] = 1;}
}

class C extends B {
    function foo() { $this['c'] = 1;}
}

class D {
    function foo() { $this['d'] = 1;}
}

?>