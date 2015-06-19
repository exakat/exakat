<?php

class A {
    private $withoutDefault;
    private $withDefault = 2;
    private $withDefaultButRedefined = 3;
    private $resetInAnotherMethod = 3;
    private $withDefaultAndIntact;
    private $arrayWithDefault = array(1,2);
    
    function __construct($c) {
        $this->withDefaultButRedefined = 3;
        $this->withoutDefault = 3;
        $this->undefinedProperty = 4;
        $this->assignedWithVariable = $c;
        $this->arrayWithDefault[2] = 3;
    }

    function reset() {
        $this->resetInAnotherMethod = 4;
    }
}

?>