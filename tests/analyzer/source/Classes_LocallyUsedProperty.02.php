<?php

class a {
    public $localyUsed = 1;
    public $usedInChild = 2;
    public $unused = 3;
    public $usedInGrandChild = 4;
    
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