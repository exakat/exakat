<?php

class x {
    const e = 1;

    static public $x = 2;
    
    static public function method() {}
    
    function y() {
        echo x::e;
        echo \x::e;
        echo static::e;
        echo parent::e;

        echo x::$p;
        echo \x::$p;
        echo static::$p;
        echo parent::$p;

        echo x::method();
        echo \x::method();
        echo static::method();
        echo parent::method();
    }
}

?>