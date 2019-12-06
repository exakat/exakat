<?php
    function f1(?array $a1 = null) {
        if ($a1 === null) {
            $a = 1;
        } else {
            $a = 2;
        }
    }

    function f2(?array $a2 = null) {
        if ($a2 !== null) {
            $a = 1;
        } else {
            $a = 2;
        }
    }

    function f3(?array $a3 = null) {
        foo($a3);
    }

?>