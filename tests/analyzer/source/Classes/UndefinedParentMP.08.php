<?php

class definedClassA extends Silex\EventListener\ConverterListener {
    public function x() {
        parent::$someAPropertyInCL;
    }
}

class definedClassB extends definedClassA {
    public function x() {
        parent::$someBPropertyInCL;
    }
}

class definedClassC extends definedClassB {
    public function x() {
        parent::$someCPropertyInCL;
    }
}

class definedClassD {
    public function x() {
        parent::$someDPropertyLost;
    }
}

?>