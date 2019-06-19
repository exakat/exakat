<?php

class A extends b {
    function __construct($a) { }
    function foo() {
        new static;
        new self;
        new parent;

        new static();
        new self();
        new parent();
    }
}

new A;
new \A;
new A();
new \A();
new A\B();
new \A\B();
new A\B;
new \A\B;
s

?>