<?php
namespace D  {
class A {
    function x() {
        self::usedprivateByAbove();
        self::usedAnother();
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
        self::usedprivateByBelowC();
        self::usedYetAnother();
    }
}

class D extends \A\B {

    function xc() {
        self::unusedprivateByBelowD();
        self::usedYetAnother();
    }
}

class E extends C {

    function xc() {
        self::usedprivateByBelowE();
        self::usedYetAnother();
    }
}

class F extends E {

    function xc() {
        self::usedprivateByBelowF();
        self::usedYetAnother();
    }
}
}

?>