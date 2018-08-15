<?php

class A {
    function foo() { $this->a; }
}

class AB {
    public $a = 1;
    public $ab = 1;
    public $abc = 1;
    public $abcd = 1;
}

class AB {
    function foo() { $this->abc; }
    function foo3() { $a->ab; }
    function foo2() { $a->abcd; }
}



?>