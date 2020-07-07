<?php

class x {
    private $withDefault = array();
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
    private $withPHPReturnedType;

    private $nothingToSay;
    
    function foo($a = array(), array $b) {
        $this->withCalculatedDefault = array();
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
        $this->withPHPReturnedType = array_diff();
    }
}

function bar() : array {}

?>