<?php

class A {
    protected $b;

    public function __construct(B $b) {
        $this->b = $b;
    }
}

class B {
    public $a;

    protected function setA(A $a) {
        $this->a = $a;
    }
}

class C {
    public $a;

    protected function setA(A $a) {
        $this->a = $a;
    }
}
