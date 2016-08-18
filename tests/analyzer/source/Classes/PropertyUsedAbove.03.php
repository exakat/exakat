<?php

class A {
    function x() {
        self::$usedProtectedByAbove;
        self::$usedAnother;
    }
}

class B extends A {
    protected static $usedProtectedByAbove;
    protected static $unusedProtected;
}

?>