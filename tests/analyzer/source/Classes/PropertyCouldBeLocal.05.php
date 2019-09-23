<?php

class x {
    static private $a = 1;
    static private $b = 2;
    static private $c = 3;

    static private $a1 = 1;
    static private $b1 = 2;
    static private $c1 = 3;
    
    static function foo() {
        $b::$a = 1;
        self::$b = 1;
        static::$c = 1;

        $b::$a1 = 1;
        self::$b1 = 1;
        static::$c1 = 1;
    }

    function foo2() {
        $b::$a1 = 1;
        self::$b1 = 1;
        static::$c1 = 1;
    }
}

?>