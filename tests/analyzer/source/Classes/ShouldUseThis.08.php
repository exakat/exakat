<?php

class A extends \Exception {
    public function __construct($a, $b) {
        parent::__construct($a, 0, null);
    }

    public function c($a, $b) {
        PARENT::__construct($a, 0, null);
    }

    public function e($a, $b) {
        PARENT::CONSTANT;
    }

    public function f($a, $b) {
        return $a + $b;
    }
}

?>