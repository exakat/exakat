<?php
namespace D  {
class A {
    function x() {
        $this->usedprivateByAbove();
        $this->usedAnother();
    }
}

class B extends A {
    private static function usedprivateByAbove() {}
    private static function unusedprivate() {}
    private static function usedprivateByBelowC() {}
    private static function unusedprivateByBelowD() {}
    private static function usedprivateByBelowE() {}
    private static function usedprivateByBelowF() {}
}

class C extends B {
    function xc() {
        $this->usedprivateByBelowC();
        $this->usedYetAnother();
    }
}

class D extends \A\B {

    function xc() {
        $this->unusedprivateByBelowD();
        $this->usedYetAnother();
    }
}

class E extends C {

    function xc() {
        $this->usedprivateByBelowE();
        $this->usedYetAnother();
    }
}

class F extends E {

    function xc() {
        $this->usedprivateByBelowF();
        $this->usedYetAnother();
    }
}
}

?>