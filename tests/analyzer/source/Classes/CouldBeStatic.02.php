<?php

class x {
    function couldBeStatic($c) {
        return 1 + $c;
    }

    function __construct() {
        $this->a = 3;
    }

    function __clone() {
        $this->a = 3;
    }

    function __other() {
        return array();
    }

    function emptyMethod() {
    }

    function constant() {
        return true;
    }
}

?>