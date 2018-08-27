<?php

trait A {
    public function A() {}
    public function C() {}
}

trait B {
    public function B() {}
    public function C() {}
}

class C {
    use  A, B;
}

class C1 {
    use  A;
}

class C2 {
    use  B;
}

class C3 {}

(new C())->D();

?>