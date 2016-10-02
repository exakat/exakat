<?php

trait t {
    public $bar = 1;
    
    function foo() {
        $this->bar = 2;
    }
}

trait t2 {
    public $bar = 1;
    
    function foo() {
        $this->bar = 3;
        $this->bar2 = 4;
    }
}


?>