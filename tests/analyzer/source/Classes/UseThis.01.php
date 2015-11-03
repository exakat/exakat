<?php

class useThis {
    function nothing() {
        $THIS = 2;
    }

    static public function nothingButStatic() {
    
    }

    public static function nothingButStatic2() {
    
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