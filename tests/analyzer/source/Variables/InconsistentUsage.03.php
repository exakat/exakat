<?php

class x {
    private $p1 = 1;

    private $p2 = null;
    private $p2a = 'a';

    private $p3 = array();
    private $p3a = 3;

    private $p4 ;
    private $p4a;
    
    function foo() {
        $this->p1 = 1;

        $this->p2 = new c;
        $this->p2->b = 1;

        $this->p2a = new c;
        $this->p2a->b = 1;

        $this->p3['b'] = 1;
        $this->p3a['b'] = 1;

        $this->p4 = new c;
        $this->p4->b = 1;

        $this->p4a = new c;
        $this->p4a = 1;
    }

}
?>