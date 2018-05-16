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

?>