<?php

class x {
    private $a = [-8 => 3];

    function foo() {
        $this->w = [5 => 2];
        $this->x = [-5 => 2];
        $this->x[] = 3;
        
        $this->y = [-6 => 2];
        
        $this->z = [-7];
        $this->z[] = 3;
        
        $this->a[] = 3;
    }
}


print_r($x);

?>