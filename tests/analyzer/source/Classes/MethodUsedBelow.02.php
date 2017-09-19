<?php
namespace D  {
class A {
    function x() {
        $this->usedprivateByAbove();
        $this->usedAnother();
    }
}

class B extends A {
    private function usedprivateByAbove() {}
    private function unusedprivate() {}
    private function usedprivateByBelowC() {}
    private function unusedprivateByBelowD() {}
    private function usedprivateByBelowE() {}
    private function usedprivateByBelowF() {}
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