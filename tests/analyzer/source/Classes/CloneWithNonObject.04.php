<?php

class x {
    private $y = null;
    private $z = 4;
    private $a;
    private $b;
    
    function __construct($b) {
        $this->y = new Y;
        $this->b = $b;
    }
    
    function foo() {
        clone $this->y;
        clone $this->z;
        clone $a;
        clone $b;
    }
}

//$a = $b == 2 ? 5 : $a = 3;

?>