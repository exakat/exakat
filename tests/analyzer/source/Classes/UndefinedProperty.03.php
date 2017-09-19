<?php

trait t {
    private $a = array();
    private $d = array();
}

class c {
    use t;
    private $o = array();

    function foo() {
        $this->a['a'] = 3;
        $this->d[] = 4;
        
        $this->b['a'] = 3;
        $this->c[] = 3;
        
        print_r($this);
    }
}
?>