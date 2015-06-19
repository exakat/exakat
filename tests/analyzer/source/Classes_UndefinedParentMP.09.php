<?php

class A extends Crada\Apidoc\Response {
    public function x() {
        parent::someAMethodInCrada();
    }
}

class B extends A {
    public function x() {
        parent::someBMethodInCrada();
    }
}

class C extends B {
    public function x() {
        parent::someCMethodInCrada();
    }
}

class D {
    public function x() {
        parent::someDMethodLost();
    }
}

?>