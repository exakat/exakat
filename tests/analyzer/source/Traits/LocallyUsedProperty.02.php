<?php

trait a {
    public $localyUsed = 1;
    public $usedInChild = 2;
    public $unused = 3;
    public $usedInGrandChild = 4;
    
    function b() {
        $this->localyUsed = 2;
    }
}

trait b {
    use b;
    function c() {
        $this->usedInChild = 3;
    }
}

trait c {
    use b; 
    
    function d() {
        $this->usedInGrandChild = 3;
    }
}

?>