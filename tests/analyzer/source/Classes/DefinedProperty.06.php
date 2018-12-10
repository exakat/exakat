<?php

trait t {
    private $pprivate = 1;
    protected $pprotected = 2;
}

class x {
    use t;
    
    function foo() {
        $this->pprivate = 3;
        $this->pprotected = 3;
    }
}

class xx extends x {
    function foo() {
        $this->pprivate = 3;
        $this->pprotected = 3;
    }
}

?>