<?php
trait t {
    private $privateM = 1;
    private $privateM2 = 2;
    
    private static $privateStaticM3 = 3;
    private static $privateStaticM32 = 4;

    private static $privateStaticM42 = 6 ;
    private static $privateStaticM5 = 7;
    private static $privateStaticM52  = 8;
    private static $privateStaticM6 = 9 ;
    private static $privateStaticM62 = 10 ;
    private static $privateStaticM72 = 12 ;

    public $publicM4;
    protected $protectedM5;

    function nonPPPM6() {
        echo $this->privateM;
        echo $this->privateStaticM3;

        echo $object->$privateStaticM32; // actually, another object

        echo self::$privateStaticM5;
        echo static::$privateStaticM6;

    }
}
?>