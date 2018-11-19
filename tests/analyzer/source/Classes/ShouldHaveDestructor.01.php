<?php

class x {
    private $p = null; 
    
    function foo() {
         $this->p->p = 2;
    }
}

class x2 {
    private $p = null; 
    
    function foo() {
         $this->p->m();
    }
}

class y1 {
    private $p = null; 
    
    function __destruct() {}
    
    function foo() {
         $this->p->m();
    }
}

class y2 {
    private $p = null; 
    
    function __destruct() {}
    
    function foo() {
         $this->p->p;
    }
}


?>