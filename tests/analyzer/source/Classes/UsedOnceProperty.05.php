<?php

class x {
    function foo() {
        $this->p2 = 2;
    }
}

class y extends x {
    protected $p1 = 1;
    protected $p2 = 2;
    protected $p3 = 2;
    
    function bar() {
        $this->p1 = 2;
        $this->p2 = 2;
        $this->p3 = 2;
        $this->p3 = 2;
    }
}



?>