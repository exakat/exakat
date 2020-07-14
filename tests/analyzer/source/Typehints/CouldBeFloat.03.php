<?php

class x {
    private $withDefault = 4.3;
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
    private $withPHPReturnedType;
    
    private $withShortAssignation;
    private $withShortAssignation2;

    private $nothingToSay;
    
    function foo($a = 5.5, float $b) {
        $this->withCalculatedDefault = 1.2;
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
        $this->withPHPReturnedType = pow(3.1, 4.2);

        $this->withShortAssignation += 2.3;
        $d *= $this->withShortAssignation2;
    }
}

function bar() : float {}

?>