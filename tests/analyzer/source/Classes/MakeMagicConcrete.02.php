<?php

class x implements xi {
    function __get($a) {}

    function foo(x $a, xi $b) {
        $a = $a->a1 + $this->a2 + $b->a3 + $this->a4;
        $a = $a->a1 + $this->a2 + $b->a3 + $a->a5;
        
    }

}

interface xi {

}
?>