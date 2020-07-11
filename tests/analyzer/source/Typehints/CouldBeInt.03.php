<?php

class x {
    private $withDefault = 4;
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
    private $withPHPReturnedType;
    
    private $withShortAssignation;
    private $withShortAssignation2;

    private $nothingToSay;
    
    function foo($a = 5, int $b) {
        $this->withCalculatedDefault = 1;
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
        $this->withPHPReturnedType = pow(3, 4);

        $this->withShortAssignation += 2;
        $d *= $this->withShortAssignation2;
    }
}

function bar() : int {}

?>