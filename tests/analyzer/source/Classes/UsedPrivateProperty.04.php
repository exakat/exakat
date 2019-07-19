<?php

trait t {
    private $privatep;
    private $privatepa = array();
    private static $privatepsself = 1;
    private static $privatepsstatic = 1;
    private $privateUnused;
    public $publicp = array();
    
    function __construct() {
        $a = $this->privatep - 2;
        $b = $this->privatepa + 3;
        
        $c = 'a' . self::$privatepsself;
        foo(static::$privatepsstatic);
    }
}
?>