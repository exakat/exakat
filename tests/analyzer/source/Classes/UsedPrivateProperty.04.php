<?php

class x {
    private $privatep;
    private $privatepa = array();
    private static $privatepsself = 1;
    private static $privatepsstatic = 1;
    private $privateUnused;
    public $publicp = array();
    
    function __construct() {
        $this->privatep = 2;
        $this->privatepa[] = 3;
        
        self::$privatepsself = 4;
        static::$privatepsstatic = 5;
    }
}
?>