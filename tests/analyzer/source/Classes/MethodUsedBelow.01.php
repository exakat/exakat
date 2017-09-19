<?php
namespace D  {
class A {
    function x() {
        $this->usedProtectedByAbove();
        $this->usedAnother();
    }
}

class B extends A {
    protected function usedProtectedByAbove() {}
    protected function unusedProtected() {}
    protected function usedProtectedByBelowC() {}
    protected function unusedProtectedByBelowD() {}
    protected function usedProtectedByBelowE() {}
    protected function usedProtectedByBelowF() {}
}

class C extends B {
    function xc() {
        $this->usedProtectedByBelowC();
        $this->usedYetAnother();
    }
}

class D extends \A\B {

    function xc() {
        $this->unusedProtectedByBelowD();
        $this->usedYetAnother();
    }
}

class E extends C {

    function xc() {
        $this->usedProtectedByBelowE();
        $this->usedYetAnother();
    }
}

class F extends E {

    function xc() {
        $this->usedProtectedByBelowF();
        $this->usedYetAnother();
    }
}
}

?>