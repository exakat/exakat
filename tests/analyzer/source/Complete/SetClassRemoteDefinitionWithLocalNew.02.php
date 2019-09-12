<?php

trait t {
    public $pT;
    function fooT() {}
}

class ba {
    public $pba;
    function fooB() {}
}

class a extends BA {
    public $pa;
    use T; 
    function fooA() {}
}

function foo() {
    $a = new A();
    
    $a->pa = 1;
    $a->pba = 2;
    $a->pT  = 3;
    $a->pe = 4;
}

?>