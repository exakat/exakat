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
    function __clone() {
        $this->usedInChild = 3;
    }
}

class c extends b {
    function __clone() {
        $this->usedInGrandChild = 3;
    }
}

?>