<?php

trait t {
    public static $bar = 1;
    
    function foo() {
        t::$bar = 2;
    }
}

trait t2 {
    public static $bar = 1;
    
    function foo() {
        t2::$bar = 3;
        t2::$bar2 = 4;
    }
}


?>