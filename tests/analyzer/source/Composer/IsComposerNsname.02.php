<?php

class definedClassA extends Silex\EventListener\ConverterListener {
    public function x() {
        parent::$someAPropertyInCL;
    }
}

class definedClassA extends Salex\EventListener\ConverterListener {
    public function x() {
        parent::$someAPropertyInCL;
    }
}

?>