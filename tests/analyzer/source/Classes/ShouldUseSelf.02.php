<?php

namespace A\B;

class x {
    const e = 1;

    static public $x = 2;
    
    static public function method() {}
    
    function y() {
        echo x::e;
        echo \A\B\x::e;
        echo static::e;
        echo parent::e;

        echo x::$p;
        echo \A\B\x::$p;
        echo static::$p;
        echo parent::$p;

        echo x::method();
        echo \A\B\x::method();
        echo static::method();
        echo parent::method();
    }
}

?>