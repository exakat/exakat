<?php

class x {
    static $staticPropertySelf = 1;
    static $staticPropertyStatic = 2;
    static $staticPropertyx = 3;
    static $staticPropertyxFNS = 4;
    static $staticPropertyUnused = 5;
    
    function y () {
        self::$staticPropertySelf = 6;
        static::$staticPropertyStatic = 7;
        x::$staticPropertyx = 8;
        \x::$staticPropertyxFNS = 9;
        \otherClass::$staticPropertyxFNS = 10;
        parent::$staticPropertySelf = 11;
    }
}

?>