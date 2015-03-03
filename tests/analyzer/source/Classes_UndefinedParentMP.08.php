<?php

class A extends Crada\Apidoc\Response {
    public function x() {
        parent::$someAPropertyInCrada;
    }
}

class B extends A {
    public function x() {
        parent::$someBPropertyInCrada;
    }
}

class C extends B {
    public function x() {
        parent::$someCPropertyInCrada;
    }
}

class D {
    public function x() {
        parent::$someDPropertyLost;
    }
}

?>