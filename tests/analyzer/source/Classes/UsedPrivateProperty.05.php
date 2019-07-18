<?php

class x {
    private $privatep;
    private $privatepa = array();
    private static $privatepsself = 1;
    private static $privatepsstatic = 1;
    private $privateUnused;
    private $privateRead;
    public $publicp = array();
    
    function y() {
        $this->privatep = 2 + $this->privateRead;
        $this->privatepa[] = 3;
        $this->virtual = 3;
        
        self::$privatepsself = 4;
        static::$privatepsstatic = 5;
    }
}
?>