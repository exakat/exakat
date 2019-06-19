<?php

// Closure are KO without class context
$a1 = function () {
    $this->ko;
};

// Static Closure are KO
$a2 = static function () {
    $this->ko;
};

$x = new x;
$x->y($a1);
$x->y($a2);

class x {
    private $ok = 'OK';

    function y($f) {
        // Closure are OK
        $a1 = function () {
            $this->ok;
        };
        
        // Static Closure are KO
        $a2 = static function () {
            $this->ok;
        };
    }
}

trait t {
    private $ok = 'OK';

    function y($f) {
        // Closure are OK
        $a1 = function () {
            $this->ok;
        };
        
        // Static Closure are KO
        $a2 = static function () {
            $this->ok;
        };
    }
}

?>