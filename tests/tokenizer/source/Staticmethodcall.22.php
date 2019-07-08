<?php
class C {
    static public function C1() {}
}

class B extends c {
    static function foo() {
        echo static::A1() + static::C1() + B::A1();
    }
}

class A extends B {
    static public function A1() {}
}


echo A::foo();

?>