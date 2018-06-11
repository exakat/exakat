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
        $this->privateM;
        $this->privateStaticM3;

        $object->$privateStaticM32; // actually, another object

        y::$privateStaticM4;
        self::$privateStaticM5;
        static::$privateStaticM6;
        \y::$privateStaticM7;

    }
}
?>