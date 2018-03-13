<?php


class x {
    static public $a = 1;
    static protected $b = 2;
    static private $c = 2;
    
    static function a() {
        self::$a = 1;
    }

    static function ab() {
        self::$a = 1;
        self::$b = 1;
    }

    static function abc() {
        self::$a = 1;
        self::$b = 1;
        self::$c = 1;
    }

}