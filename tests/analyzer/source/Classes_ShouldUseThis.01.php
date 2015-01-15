<?php

class useThis {
    function nothing() {
        $THIS = 2;
    }

    static public function nothingButStatic() {
        static::$a = 2;
    }

    public static function nothingButStatic2() {
        static::$b = 1;
    }
    
    function property() {
        $this->a = 1;
    }

    function method() {
        $this->b();
    }

    function bothpropertyandmethod() {
        $this->c($this->d);
    }
}
?>