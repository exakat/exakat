<?php

trait a {
    protected $localyUsed = 1;
    protected $usedInChild = 2;
    protected $unused = 3;
    protected $usedInGrandChild = 4;
    
    function b() {
        $this->localyUsed = 2;
    }
}

trait b {
    use a;
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