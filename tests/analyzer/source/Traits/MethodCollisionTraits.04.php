<?php

trait A {
    public function A() {}
    public function C() {}
    public function D() {}
}

trait B {
    public function B() {}
    public function C() {}
    public function D() {}
}

class C {
    use  A, B {
        A::D insteadof B;
    }
}

class CC {
    use  A, B {
        B::D insteadof A;
        B::C insteadof A;
    }
}

class CCC {
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