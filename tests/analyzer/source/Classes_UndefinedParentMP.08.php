<?php

class definedClassA extends Silex\EventListener\ConverterListener {
    public function x() {
        parent::$someAPropertyInCrada;
    }
}

class definedClassB extends definedClassA {
    public function x() {
        parent::$someBPropertyInCrada;
    }
}

class definedClassC extends definedClassB {
    public function x() {
        parent::$someCPropertyInCrada;
    }
}

class definedClassD {
    public function x() {
        parent::$someDPropertyLost;
    }
}

?>