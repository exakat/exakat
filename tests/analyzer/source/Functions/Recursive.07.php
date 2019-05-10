<?php

class x {
    function __clone() {
        $this->__clone();
    }

    function __set($a, $b) {
        self::__set($a, $b);
    }

    function __get($a) {
        self::__set($a, $b);
    }
}


class x2 {
    function __CLONE() {
        $this->__clone();
    }

    function __SET($a, $b) {
        self::__set($a, $b);
    }

    function __GET($a) {
        self::__set($a, $b);
    }
}

?>