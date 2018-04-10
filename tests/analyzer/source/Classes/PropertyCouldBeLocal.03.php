<?php

class x {
    static private $twice = 1;
    static private $once = 1;
    static private $external = 1;
    static   $onceButPublic = 1;
    
    public function one() {
        self::$once = 2;
        self::$twice = 2;
        self::$external = 2;
        self::$external = 2;
        static::$onceButPublic = 2;
    }
    
    function two() {
        self::$twice = 2;
        OTHER_CLASS::$external = 2;
    }
}
?>