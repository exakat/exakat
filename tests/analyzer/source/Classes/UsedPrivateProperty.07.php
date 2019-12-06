<?php

trait t {
    private $pt1, $pt2;

    function foo() {
        return $this->pt1 + $this->p1;
    }
}

class x {
    use t;
    
    private $p1, $p2;
    
    function bar() {
        return $this->pt2 * $this->p2;
    }
}

?>