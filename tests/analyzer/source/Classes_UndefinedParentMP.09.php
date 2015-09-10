<?php

class definedClassA extends Crada\Apidoc\Response {
    public function x() {
        parent::someAMethodInCrada();
    }
}

class definedClassB extends definedClassA {
    public function x() {
        parent::someBMethodInCrada();
    }
}

class definedClassC extends definedClassB {
    public function x() {
        parent::someCMethodInCrada();
    }
}

class definedClassD {
    public function x() {
        parent::someDMethodLost();
    }
}

?>