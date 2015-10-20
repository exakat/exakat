<?php

class A {
    private $singleton = null;
    
    private function __construct() { }
    
    private function __destruct() { }
    
    static function factory() {
        if (self::$singleton === null) {
            self::$singleton = new a();
        }
        
        return self::$singleton;
    }
}

class B {
    protected function __construct() { }
    
    private function __destruct() { }
}

class C {
    public function __construct() { }
    
    private function __destruct() { }
}


?>