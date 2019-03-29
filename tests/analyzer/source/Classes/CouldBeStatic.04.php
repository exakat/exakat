<?php

abstract class x {
    function couldBeStatic() {
        return rand(3,4);
    }

    abstract function isAbstract();

    function couldNotBeStatic() {
        return $this->boo;
    }
}

$a = function ($closure) {
        return $this->boo;
    };

function aFunction() {
        return $boo;
    };
?>