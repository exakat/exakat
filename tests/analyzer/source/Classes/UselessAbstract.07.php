<?php

abstract class A {
    const B = 'D';
    const C = 'E';
}
class AA extends A {}


abstract class B {
    use T, TT;
}

class BB extends B {}

abstract class C {
    public $c;
}

class CC extends C {}

abstract class D {
    use t;
    abstract public function __clone();
             public function clone() {}
}

class DD extends D {
             public function __clone() {}
             public function clone() {}
}

abstract class E {
    use t;
             public function __clone() {}
             public function clone() {}
}

class EE extends E {
}

abstract class abstractClass8 {
    function foo() { }
}

?>