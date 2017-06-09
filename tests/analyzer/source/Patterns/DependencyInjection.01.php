<?php

class A {
    public function __construct(B $b, $c, D $d, E $e, F $f) {
        $this->b = $b;
        A::$c = $c;
        self::$d = $d;
        A::$e = $e;
        $this->f = $f->toString();
    }

    public function foo(B $b2, $c2, D $d2, E $e2, F $f2) {
        $this->b = $b;
        A::$c = $c;
        self::$d = $d;
        A::$e = $e;
        $this->f = $f->toString();
    }
}

?>