<?php

$a = new class implements Logger { 
    private static $foo;
    private static $foo2 = 1;
    static private $foo3;
    static private $foo4 = 1;

    private $foo5 = 1;
    static  $foo6;
    
    static A $foo7;
    static B\B $foo8;

    function foo() {
        static $x = 1;
    }
}

?>