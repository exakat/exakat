<?php

class x {
    private $p, $p2, $p3, $p4, $p5, $p6, $p7, $p8;
    
    function foo() {
        $this->p->a = 2;
        $this->p2->m(2);
        echo $this->p3::C;
        $this->p4::$C = 2;
        $this->p5::C(2);
        $this->p6 = 2;
        $c instanceof $this->p7;
    }
}

?>