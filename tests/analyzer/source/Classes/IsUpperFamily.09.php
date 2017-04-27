<?php

class AA {
    static protected function inAAMethod() {}
    static protected $inAAProperty;
    const inAAConst = 1;
}

class A extends AA {
    use t; 
    
    static function inA() {}
    
    function foo() {
        $a::inAAMethod();
        self::inAAMethod();

        $a::$inAAProperty();
        self::$inAAProperty;

        $a::inAAConst;
        self::inAAConst;
    }
}

?>