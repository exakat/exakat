<?php

class x {
    private $withDefault = "t";
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
    private $withPHPReturnedType;

    private $nothingToSay;
    
    function foo($a = "d", string $b) {
        $this->withCalculatedDefault = 'c';
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
        $this->withPHPReturnedType = strtolower($a);
    }
}

function bar() : string {}

?>