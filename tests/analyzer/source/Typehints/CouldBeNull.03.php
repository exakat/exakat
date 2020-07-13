<?php

class x {
    private $withDefault = null;
    private $withCalculatedDefault;
    private $withRelayedDefault;

    private $withRelayedType;

    private $withReturnedType;
    private $withPHPReturnedType;
    
    private $withCoalesce;
    private $withCoalesce2;

    private $nothingToSay;
    
    function foo($a = null, ?int $b) {
        $this->withCalculatedDefault = null;
        
        $this->withRelayedDefault = $a;
        $this->withRelayedType = $b;

        $this->withReturnedType = bar();
        $this->withPHPReturnedType = shell_exec('');

        $this->withCoalesce ??= 2;
        $d = $this->withCoalesce2 ?? '';
    }
}

function bar() : ?int {}

?>