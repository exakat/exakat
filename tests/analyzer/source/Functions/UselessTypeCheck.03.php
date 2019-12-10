<?php
    function f1(?array $a1 = null) {
        if (is_null($a1)) {
            $a = 1;
        } else {
            $a = 2;
        }
    }

    function f2(?array $a2 = []) {
        if (is_null($a2)) {
            $a = 1;
        } else {
            $a = 2;
        }
    }

    function f4(int $a4) {
        if (is_null($a4)) {
            $a = 1;
        } else {
            $a = 2;
        }
    }

?>