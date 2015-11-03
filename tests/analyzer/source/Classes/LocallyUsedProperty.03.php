<?php

class a {
    private $localyUsed = 1;
    private $usedInChild = 2;
    private $unused = 3;
    private $usedInGrandChild = 4;
    
    function b() {
        $this->localyUsed = 2;
    }
}

class b extends a {
    function c() {
        $this->usedInChild = 3;
    }
}

class c extends b {
    function d() {
        $this->usedInGrandChild = 3;
    }
}

?>