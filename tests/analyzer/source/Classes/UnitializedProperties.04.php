<?php

$a = new class {
    static private $initialized = 1;
    static private $notInitialized;
    
    function __construct() {}
    
    function foo() {
        echo self::$initialized;
        echo __CLASS__;
    }
};

$a->foo();
?>