<?php

class A {
    function x() {
        $this->usedProtectedByAbove;
        $this->usedAnother;
    }
}

class B extends A {
    protected $usedProtectedByAbove;
    protected $unusedProtected;
}

?>