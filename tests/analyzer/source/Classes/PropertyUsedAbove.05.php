<?php

class A {
    function x() {
        self::$usedStaticProtectedDefaultedByAbove;
        $this->usedProtectedDefaultedByAbove;
        $this->usedProtectedByAbove;
        self::$usedAnother;
    }
}

class B extends A {
    protected static $usedStaticProtectedDefaultedByAbove = 1;
    protected static $unusedStaticProtectedDefaulted = 2;

    protected $usedProtectedDefaultedByAbove = 3;
    protected $unusedProtectedDefaulted = 4;

    protected $usedProtectedByAbove;
    protected $unusedProtected;
}

?>