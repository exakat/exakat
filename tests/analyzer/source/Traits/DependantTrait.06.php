<?php

trait t {
    public static function bar() {}
    
    function foo() {
        t::bar();
    }
}

trait t2 {
    public static function bar() {}
    
    function foo() {
        t2::bar();
        t2::bar2();
    }
}


?>