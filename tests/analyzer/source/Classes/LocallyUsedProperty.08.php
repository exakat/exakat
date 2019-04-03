<?php

class a {
    protected $localyUsed = 1;
    protected $usedInChild = 2;
    protected $unused = 3;
    protected $usedInGrandChild = 4;
    
    function b() {
        $this->localyUsed = 2;
    }
}

$a = new class extends a {
    function c() {
        $this->usedInChild = 3;
    }
}

?>