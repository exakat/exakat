<?php

class a {
    public $optionalEmptyA = null;
    public $nonEmptyA = 1;
}

class B {
    public $optionalEmptyB = null;
    public $nonEmptyB = 2;
}

class C {
    public $optionalEmptyC = null;
    public $nonEmptyC = 3;
}

function foo() : B { return new B; }

class x {
    private $optionalEmpty = null;
    private $circonstancialEmpty = 1;
    private $voidEmpty;
    private $neverEmpty = 6;
     
    function foo(A $a) {
        if (empty($a->optionalEmptyA)) {}
        if (empty($a->nonEmptyA)) {}

        $b = foo();
        if (empty($b->optionalEmptyB)) {}
        if (empty($b->nonEmptyB)) {}

        $c = new C;
        if (empty($c->optionalEmptyC)) {}
        if (empty($c->nonEmptyC)) {}
    }
}

?>