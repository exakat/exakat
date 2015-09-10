<?php

class definedClassA extends Silex\EventListener\ConverterListener {
    public function x() {
        parent::someAMethodInSilex();
    }
}

class definedClassB extends definedClassA {
    public function x() {
        parent::someBMethodInSilex();
    }
}

class definedClassC extends definedClassB {
    public function x() {
        parent::someCMethodInSilex();
    }
}

class definedClassD {
    public function x() {
        parent::someDMethodLost();
    }
}

?>