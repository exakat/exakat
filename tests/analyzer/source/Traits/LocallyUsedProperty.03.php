<?php

trait a {
    private $localyUsed = 1;
    private static $staticLocalyUsed = 1;
    private $localyUsed2 = 1;
    private static $staticLocalyUsed2 = 1;
    private $localyUsed3 = 1;
    private static $staticLocalyUsed3 = 1;
    private $usedInChild = 2;
    private $staticUsedInChild = 2;
    private $unused = 3;
    private $usedInGrandChild = 4;
    
    function b() {
        $this->localyUsed[1] = 2;
        static::$staticLocalyUsed[1] = 2;
        $this->localyUsed2[1][2] = 2;
        static::$staticLocalyUsed2[1][2] = 2;
        $this->localyUsed3[1][2][3] = 2;
        static::$staticLocalyUsed3[1][2][3] = 2;
    }
}

trait b {
    use a;
    function c() {
        $this->usedInChild = 3;
        static::$staticUsedInChild[1] = 3;
    }
}

trait c {
    use b; 
    
    function d() {
        $this->usedInGrandChild = 3;
    }
}

?>