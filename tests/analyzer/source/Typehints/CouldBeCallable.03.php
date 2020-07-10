<?php

class x {
//    private $withDefault = true;
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
    private $withPHPReturnedType;

    private $nothingToSay;
    
    function foo($a = true, callable $b) {
        $this->withCalculatedDefault = function () {};
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
//        $this->withPHPReturnedType = is_array();
    }
}

function bar() : callable {}

?>