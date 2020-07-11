<?php

class x {
    private $withDefault = null;
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
//    private $withPHPReturnedType;

    private $nothingToSay;
    
    function foo(\X $a = null, X $b) {
        $this->withDefault = new X;
        $this->withCalculatedDefault = clone $this->withDefault;
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
//        $this->withPHPReturnedType = is_array();
    }
}

function bar() : G {}

?>