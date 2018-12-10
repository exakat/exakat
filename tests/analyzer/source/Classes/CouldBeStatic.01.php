<?php

class x {
    function couldBeStatic() {
        return 1;
    }

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