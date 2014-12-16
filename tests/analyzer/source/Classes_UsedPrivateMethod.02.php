<?php

class A {
    private function __construct() { }
    
    private function __destruct() { }
    
    static function factory() {
        if (self::$singleton === null) {
            self::$singleton = new a();
        }
        
        return self::$singleton;
    }
}

?>