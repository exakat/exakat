<?php
class C {
    static $C1 = 1;
}

class B extends c {
    static function foo() {
        echo static::$C1 + static::$A1 + B::$A1;
    }
}

class A extends B {
    static $A1 = 1;
}


echo A::foo();

?>