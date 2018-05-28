<?php

trait t {
    public $bar = 1;
    
    function __clone() {
        $this->bar = 2;
    }
}

trait t2 {
    public $bar = 1;
    
    function __clone() {
        $this->bar = 3;
        $this->bar2 = 4;
    }
}


?>