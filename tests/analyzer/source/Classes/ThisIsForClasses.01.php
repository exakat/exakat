<?php

// raw
$this->x();

function y() {
    $this->ko;
}

// Closure are OK
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
?>