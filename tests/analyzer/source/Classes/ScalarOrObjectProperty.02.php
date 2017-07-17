<?php

class x {
    public $literal = 1;
    public $object = null;
    protected $object2;
    protected $object3;
    protected $object4 = null;

    public function b() {
        $this->object = new A();
        $this->object = 1;

        $this->object2 = new A();
        $this->object2 = 1;

        $this->object3 = new A();
        $this->object3 = new A();
        $this->object3 = new A();
        $this->object3 = 1;

        $this->object4 = new A();
        $this->object4 = 1;
        $this->object4 = 1;
        $this->object4 = 1;
        $this->object4 = 1;
    }

}