<?php
namespace D  {
class A {
    function x() {
        $this->usedProtectedByAbove;
        $this->usedAnother;
    }
}

class B extends A {
    protected $usedProtectedByAbove;
    protected $unusedProtected;
    protected $usedProtectedByBelowC;
    protected $unusedProtectedByBelowD;
    protected $usedProtectedByBelowE;
    protected $usedProtectedByBelowF;
    protected $usedProtected;
    
    function xb() {
        $this->usedProtected =3;
    }
}

class C extends B {
    function xc() {
        $this->usedProtectedByBelowC;
        $this->usedYetAnother;
    }
}

class D extends \A\B {

    function xc() {
        $this->unusedProtectedByBelowD;
        $this->usedYetAnother;
    }
}

class E extends C {

    function xc() {
        $this->usedProtectedByBelowE;
        $this->usedYetAnother;
    }
}

class F extends E {

    function xc() {
        $this->usedProtectedByBelowF;
        $this->usedYetAnother;
    }
}
}

?>