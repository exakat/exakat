<?php

trait t {
    abstract public function foo();
}

abstract class BT {
    use t;
    
    function foo2() {}
}

abstract class B {
    use t;
    
    function foo2() {}
}

class C extends b {
    function foo() {}
}

new C();

abstract class C2 {
    function foo() {
        $this->undefined = 3;
    }
}

abstract class C3 {
    private $defined = 3;
    function foo() {
        $this->defined = 3;
    }
}

?>