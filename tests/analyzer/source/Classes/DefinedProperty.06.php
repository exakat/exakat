<?php

trait t {
    private $pprivate = 1;
    protected $pprotected = 2;

    private $pprivate2 = 1;
    protected $pprotected2 = 2;
}

class x {
    use t;

    private $pprivate3 = 1;
    protected $pprotected3 = 2;
    
    function foo() {
        $this->pprivate = 3;
        $this->pprotected = 3;
    }
}

class xx extends x {
    function foo() {
        $this->pprivate2 = 3;
        $this->pprotected2 = 3;

        $this->pprivate3 = 3;
        $this->pprotected3 = 3;
    }
}

?>