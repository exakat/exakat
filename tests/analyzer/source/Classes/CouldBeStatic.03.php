<?php

class x {
    function couldBeStatic() {
        return rand(3,4);
    }

    static function isStatic() {
        return rand(3,4);
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