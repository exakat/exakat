<?php

trait t {
    function b() { echo __METHOD__;}
}

class x {
    use t {
        b as c;
        t::b as d;
    }
    
    function foo() {
        $this->c();
        $this->d();

        self::c();
        self::d();
    }
}

(new x)->foo();
?>