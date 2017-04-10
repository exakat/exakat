<?php

class x {
    function methodUseThis() {
        $this->a = 1;
    }

    function methodDontUseThis() {
        return function ($ab) {
            return $a + 1;
        };
        $b = 2;

        function c($d) { 
            $e;
            function g($f) {
                $i;
            }
        }

    }
}
?>