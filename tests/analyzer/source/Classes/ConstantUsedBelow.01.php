<?php
namespace D  {
class A {
    function x() {
        $this->USEDPROTECTEDBYABOVE;
        $this->USEDANOTHER;
    }
}

class B extends A {
    protected const USEDPROTECTEDBYABOVE    = 1;
    protected const UNUSEDPROTECTED         = 2;
    protected const USEDPROTECTEDBYBELOWC   = 3;
    protected const UNUSEDPROTECTEDBYBELOWD = 4;
    protected const USEDPROTECTEDBYBELOWE   = 5;
    protected const USEDPROTECTEDBYBELOWF   = 6;
}

class C extends B {
    function xc() {
        self::USEDPROTECTEDBYBELOWC;
        self::USEDYETANOTHER;
    }
}

class D extends \A\B {

    function xc() {
        self::UNUSEDPROTECTEDBYBELOWD;
        self::USEDYETANOTHER;
    }
}

class E extends C {

    function xc() {
        self::USEDPROTECTEDBYBELOWE;
        self::USEDYETANOTHER;
    }
}

class F extends E {

    function xc() {
        self::USEDPROTECTEDBYBELOWF;
        self::USEDYETANOTHER;
    }
}
}

?>