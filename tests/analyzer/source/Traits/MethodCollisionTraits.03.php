<?php

trait A {
    public function A() {}
    public function C() {}
}

trait B {
    public function B() {}
    public function C() {}
}

trait D {
    public function D() {}
    public function C() {}
}

class C {
    use  A, B, D;
}

class C1 {
    use D;
}

(new C())->D();

?>