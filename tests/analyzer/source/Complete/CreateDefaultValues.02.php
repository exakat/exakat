<?php

// $a, $b, $c, $d are not tested, as Variabledefinition is not reported
class x {
    protected $p1, $p2, $p3;

    function foo() {
        $a = $b = $c = 3;
        $this->p1 = $this->p2 = $this->p3 = 4;
        $d = foo();
    }
}

?>