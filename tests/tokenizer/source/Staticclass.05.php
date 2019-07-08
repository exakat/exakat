<?php
class C {
    const C = 2;
}

class B extends c {
    static function foo() {
        echo static::A + static::C + B::A;
    }
}

class A extends B {
    const A = 1;
}


echo A::foo();

?>