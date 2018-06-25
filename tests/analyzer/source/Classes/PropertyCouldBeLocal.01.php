<?php


class x {
    public $a = 1;
    protected $b = 2;
    private $c = 3;
    
    function a() {
        $this->a = 1;
    }

    function ab() {
        $this->a = 1;
        $this->b = 1;
    }

    function abc() {
        $this->a = 1;
        $this->b = 1;
        $this->c = 1;
    }

}