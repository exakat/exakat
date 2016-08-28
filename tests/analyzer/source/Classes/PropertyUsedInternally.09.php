<?php

static $noClass = 1;

class foo {
    static $withClass = 2;
    
    function bar() {
        foo::$noClass = 3;
        foo::$withClass = 4;
        self::$noClass = 5;
    }
}


?>