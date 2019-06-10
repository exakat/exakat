<?php

class x {
    static private $d, $a2, $a;
    
    function foo() {
        // PHP 5.5+ empty() usage
        self::$d = strtolower($b0 . $c0);
        if (empty(strtolower($b0 . $c0))) {
            doSomethingWithoutA();
        }
    }
    
    function foo2() {
        // Compatible empty() usage
        self::$a = strtolower($b . $c);
        if (empty(self::$a)) {
            doSomethingWithoutA();
        }
    }
    
    function foo3() {
        // $a is reused
        self::$a2 = strtolower($b . $c);
        if (empty(self::$a2)) {
            doSomethingWithoutA();
        } else {
            echo self::$a2;
        }
    }
}
?>