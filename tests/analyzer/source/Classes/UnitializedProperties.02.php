<?php

class X {
    private static $i1 = 1, $i2;
    protected static $u1, $u2;
    
    function __construct() {
        static::$i2 = 1 + self::$u2;
    }
    
    function m() {
        echo static::$i1, self::i2, X::$u1, \X::$u2;
    }
}
?>