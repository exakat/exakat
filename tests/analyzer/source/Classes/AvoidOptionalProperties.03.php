<?php

class x {
    private $optionalEmpty = null;
    private $circonstancialEmpty = 1;
    private $voidEmpty;
    private $neverEmpty = 6;
     
    function foo() {
        if (empty($this->optionalEmpty)) {}
        
        $this->circonstancialEmpty = null;
        if (empty($this->circonstancialEmpty)) {}
        
        if (empty($this->voidEmpty)) {}

        if (empty($this->neverEmpty)) {}
        $this->neverEmpty = 3;
    }
}

?>