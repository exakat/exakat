<?php

class x {
    private $withRelayedType;
    private $withForeach;
    private $withYieldFrom;

    private $withReturnedType;
    private $withPHPReturnedType;
    
    private $withArraySyntax;

    private $nothingToSay;
    
    function foo($a = array(), iterable $b) {
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();

        $this->withArraySyntax[1] = 2;
        
        yield from $this->withYieldFrom;
        
        foreach($this->withForeach as $b) {}
    }
}

function bar() : iterable {}

?>