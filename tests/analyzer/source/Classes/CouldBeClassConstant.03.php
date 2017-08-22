<?php

class x {
    static $emptyArray     = array();
    static $nonEmptyArray  = array(1);
    static $integer        = 1;
    static $boolean        = true;
    static $string         = 'Indeed a string';
    
    function y() {
        self::$integer;
        static::$boolean;
        self::$string + 1;
        
        static::$nonEmptyArray;
        static::$emptyArray;
        
        $a::$robustTest = 3;
    }
}

?>