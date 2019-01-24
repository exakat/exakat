<?php

class x {
    function f($b, $a = 1) {}
    
    function b() {
        $this->f();
        $this->f(1);
        $this->f(2, 3);
        $this->f(2, 3, 4);
    }
}

?>