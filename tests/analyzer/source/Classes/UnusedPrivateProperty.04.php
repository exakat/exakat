<?php
class y {
    private $privateM;
    private $privateM2;
    
    private static $privateStaticM3;
    private static $privateStaticM32;

    private static $privateStaticM4  ;
    private static $privateStaticM42  ;
    private static $privateStaticM5  ;
    private static $privateStaticM52  ;
    private static $privateStaticM6  ;
    private static $privateStaticM62  ;
    private static $privateStaticM7  ;
    private static $privateStaticM72  ;

    public $publicM4;
    protected $protectedM5;

    function __construct() {
        echo $this->privateM;
        echo $this->privateStaticM3;

        echo $object->$privateStaticM32; // actually, another object

        echo y::$privateStaticM4;
        echo self::$privateStaticM5;
        echo static::$privateStaticM6;
        echo \y::$privateStaticM7;

    }
}
?>