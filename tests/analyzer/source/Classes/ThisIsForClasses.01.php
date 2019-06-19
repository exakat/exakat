<?php

// raw
$this->x();

function y() {
    $this->ko;
}

// Closure are KO, when outside a class/trait
function () {
    $this->ko;
};

class x {

    function z() {
        $this->ok;
    }

    // test for magicmethod
    function __construct() {
        $this->ok;
    }
}

trait t {

    function z() {
        $this->ok;
    }

    // test for magicmethod
    function __construct() {
        $this->ok;
    }
}
?>