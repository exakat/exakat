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