<?php

class x {
    private $p1 = 1;
    private $p2 = 2;
    private $p3 = 3;
    private $p4 = 4;
    
    function foo() {
        $this->p1 = 1;
        $this->p2 = 1;
        $this->p3['b'] = 1;
        $this->p4 = 1;
    }

    function foo2() {
        $this->p1 = 1;
        $this->p2->a = 1;
        $this->p3 = 1;
        $this->p4[1] = 1;
    }

    function foo3() {
        $this->p1 = 1;
        $this->p2->a = 1;
        $this->p3 = 1;
        $this->p4->d = 1;
    }
    
}
?>