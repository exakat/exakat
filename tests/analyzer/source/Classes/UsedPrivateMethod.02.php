<?php

class A {
    private function __construct() { }
    
    private function __destruct() { }

    private function unusedMethod() { }
    
    static function factory() {
        if (self::$singleton === null) {
            self::$singleton = new a();
        }
        
        return self::$singleton;
    }
}

?>