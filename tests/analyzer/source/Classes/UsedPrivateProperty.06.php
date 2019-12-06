<?php

trait t2 {
    function foo() {
        return $this->p2;
    }
}

trait t {
    use t2;

    function foo() {
        return $this->p1;
    }
}

class x {
    use t;
    
    private $p1, $p2, $p3, $p41;
    protected $p42;
}

?>