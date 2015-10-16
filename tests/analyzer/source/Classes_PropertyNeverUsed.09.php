<?php

class w {
    static $staticPropertyStatic = 2;
    static $staticPropertyx = 3;
    static $staticPropertyxFNS = 4;
    static $staticPropertyw = 31;
    static $staticPropertywFNS = 41;
    static $staticPropertyParent = 12;

    static $staticPropertyUnused = 5;
}

class w2 extends w { }

class w1 extends w2 { }

class x extends w1 {
    
    function y () {
//        self::$staticPropertySelf = 6;
        static::$staticPropertyStatic = 7;
        parent::$staticPropertyParent = 11;

        x::$staticPropertyx = 8;
        \x::$staticPropertyxFNS = 9;
        w::$staticPropertyw = 8;
        \w::$staticPropertywFNS = 9;
        \otherClass::$staticPropertyxFNS = 10;

    }

}

$x = new x();
$x->y();

?>