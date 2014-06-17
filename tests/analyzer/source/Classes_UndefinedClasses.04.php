<?php

class x {
    static $a = 1;
    static $A = 1;
    
    function y() {
        self::$a = 1;
        parent::$a = 1;
        static::$a = 1;

        SELF::$A = 1;
        PARENT::$A = 1;
        STATIC::$A = 1;
    }
}

$x = new x();
$x->y();
?>