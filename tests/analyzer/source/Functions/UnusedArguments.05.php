<?php

interface i {
    function i1($a, $b);
    function i2($a, $b);
}

class foo1 { 
    function ffoo1($a1, $b) {
        return $a1 - $b -$a1;
    }
    function ffoo12($a1, $b) {
        return $a1 - $b -$a1;
    }
}

class foo2 extends foo1 implements i {
    function i1($a, $b) {
        return $a;
    }

    function i2($a, $b) {
        return $a + $b;
    }

    function a1($a, $b) {
        return $a + $b;
    }
    function a2($a, $b) {
        return $a;
    }

    function ffoo1($a2, $b) {
        return $a;
    }

    function ffoo12($a2, $b) {
        return $a + $b;
    }
}

    function A1($a, $b) {
        return $a + $b;
    }
    function A2($a, $b) {
        return $a;
    }

trait t {
    function at1($a, $b) {
        return $a + $b;
    }
    function at2($a, $b) {
        return $a;
    }
}

?>