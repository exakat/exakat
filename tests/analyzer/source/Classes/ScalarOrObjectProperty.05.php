<?php

class x {
    public $literal = 1;
    protected $literalA;

    private $literalB = null;
    public $object = array();
    protected $object2 = true;

    public function b() {
        $this->literal = 2;

        $this->literalA = new A;
        $this->literalA->yes = 2;

        $this->literalB = new B;
        $this->literalB->yes();
        $this->object->bar();
        $this->object2->bar;
    }

}