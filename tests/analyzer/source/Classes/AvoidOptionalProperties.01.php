<?php

class x {
    private $optionalCondition = null;
    private $optionalEmpty = null;
    private $optionalComparison = null;
    private $notOptional = null;

    function __construct(a $a = null, $b, C $c, $d = null, $e = 2) {
        $this->a = $a;
    }
    
    function foo() {
        if (empty($this->optionalEmpty)) {}
        
        if ($this->optionalComparison === null) {}
        
        if ($this->optionCondition) {}
        
        $this->notOptional = 2;
    }    
}

?>