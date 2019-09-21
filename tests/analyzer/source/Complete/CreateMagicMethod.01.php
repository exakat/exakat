<?php

interface i {}

class x0 {
    function __call($name, $args) {}
    
    static function __callStatic($name, $args) {}
}

class x extends x0 implements i {
    function __call($name, $args) {}
    
    static function __callStatic($name, $args) {}
    
    function foo(x $a, I $b, $c) {
        $this->c();
        $a->c();
        $b->c();
        $c->c();

        self::C2(1);
        static::C2(2);
        parent::C2(3);
        $this::C2(4);
        $a::C2(5);
        x::C2(6);
        \x::C2(7);

        $b::C2(7);
        $c::C2(7);
    }
}

?>