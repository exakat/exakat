<?php

class x {
    static $staticPropertySelf1 = 1, $staticPropertySelf2 = 1, $staticPropertySelf3 = 1;
    static $staticPropertyStatic1 = 2, $staticPropertyStatic2 = 2, $staticPropertyStatic3 = 2;
    static $staticPropertyx1 = 3, $staticPropertyx2 = 3, $staticPropertyx3 = 3;
    static $staticPropertyxFNS1 = 4, $staticPropertyxFNS2 = 4, $staticPropertyxFNS3 = 4;
    static $staticPropertyUnused1 = 5, $staticPropertyUnused2 = 5, $staticPropertyUnused3 = 5;
    
    function y () {
        self::$staticPropertySelf1 = 6;
        static::$staticPropertyStatic1 = 7;
        x::$staticPropertyx1 = 8;
        \x::$staticPropertyxFNS1 = 9;
        \otherClass::$staticPropertyxFNS1 = 10;
        parent::$staticPropertySelf1 = 11;

        self::$staticPropertySelf2 = 6;
        static::$staticPropertyStatic2 = 7;
        x::$staticPropertyx2 = 8;
        \x::$staticPropertyxFNS2 = 9;
        \otherClass::$staticPropertyxFNS2 = 10;
        parent::$staticPropertySelf2 = 11;

        self::$staticPropertySelf3 = 6;
        static::$staticPropertyStatic3 = 7;
        x::$staticPropertyx3 = 8;
        \x::$staticPropertyxFNS3 = 9;
        \otherClass::$staticPropertyxFNS3 = 10;
        parent::$staticPropertySelf3 = 11;
    }
}

?>