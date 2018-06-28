<?php

class x {
    static private $a = 1;
    static private $b = 2;
    static private $c = 3;
    
    static function foo() {
        $b::$a = 1;
        self::$b = 1;
        static::$c = 1;
    }
}    
?>