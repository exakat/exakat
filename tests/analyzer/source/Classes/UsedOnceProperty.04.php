<?php

class A {
    protected $f = 1;
    protected $g = 1;
    protected $h = 1;
    protected $i = 0;
    protected $j = 3;
    protected $k = 1;
    
    function foo() {
        $this->f = 3;
        $this->j = 4;

        $this->k = 4;
        $this->k = 4;
    }

    function goo() {
        $this->g = 3;
    }
}

class B extends A {
    function foo() {
        $this->f = 4;
        $this->j = 4;
    }

    function goo() {
        $this->h = 4;
        $this->j = 4;
    }
}

?>