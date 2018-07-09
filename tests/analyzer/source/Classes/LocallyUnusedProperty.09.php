<?php

class x {
    public $unusedc;
    public $usedc;

    function foo() {
        $this->usedc = 1;
    }
}

trait x {
    public $unusedt;
    public $usedt;
    
    function foo() {
        $this->usedt = 3;
    }
}


?>