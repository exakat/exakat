<?php

trait t1 {
    public $p1; 
    
    function foo() {
        $this->p1;
    }
}

trait t2 {
    function foo() {
        $this->p2;
    }
}

trait t31 {
    use t32;
    public $p31;
    
    function foo() {
        $this->p31 + $this->p32;
    }
}

trait t32 {
    public $p32;
}

?>