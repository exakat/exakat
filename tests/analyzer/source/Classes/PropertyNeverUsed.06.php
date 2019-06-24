<?php

class x {
    function y () {
//        self::$staticPropertySelf = 6;
        static::$staticPropertyStatic = 7; // Used, but declared in a child. 
// undeclared 
         x::$staticPropertyx = 8;
        \x::$staticPropertyxFNS = 9;
        w::$staticPropertyx = 8;
        \w::$staticPropertyxFNS = 9;
        \otherClass::$staticPropertyxFNS = 10;
    }
}

class w extends x {
//    static $staticPropertySelf = 1;
    static $staticPropertyStatic = 2;
    static $staticPropertyx = 3;
    static $staticPropertyxFNS = 4;
    static $staticPropertyUnused = 5;
}

$w = new w();
$w->y();

?>