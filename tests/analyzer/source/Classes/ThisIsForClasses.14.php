<?php

// Closure are KO without class context
$a1 = function () {
    $this->ko;
};

// Static Closure are KO
$a2 = static function () {
    $this->ko;
};

        $fn2 = static fn ($b) => $this->ko;
        $fn2 =        fn ($b) => $this->ok;


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

        $fn2 = static fn () => $this->ko;
        $fn2 =  fn () => $this->ok;
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

        $fn2 = static fn ($a) => $this->ko;
        $fn2 =  fn ($a) => $this->ok;

    }
}

?>