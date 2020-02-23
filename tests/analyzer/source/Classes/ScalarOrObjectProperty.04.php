<?php

class x {
    public $literal = 1;
    public $object = null;
    protected $object2;
    protected $object3;
    protected $object4 = null;
    public $object5 = null;
    public $object6 = null;

    public function b() {
        $this->object = fooTyped();
        $this->object = 1;

        $this->object2 = fooTyped();
        $this->object2 = 1;

        $this->object3 = fooTyped();
        $this->object3 = fooTyped();
        $this->object3 = fooTyped();
        $this->object3 = 1;

        $this->object4 = fooTyped();
        $this->object4 = 1;
        $this->object4 = 1;
        $this->object4 = 1;
        $this->object4 = 1;

        $this->object5 = foo();
        $this->object5 = 1;
        $this->object5 = 1;
        $this->object5 = 1;
        $this->object5 = 1;

        $this->object6 = foo_undefined();
        $this->object6 = 1;
        $this->object6 = 1;
        $this->object6 = 1;
        $this->object6 = 1;
    }

}

function foo() {}
function fooTyped() : x { return new x(); }