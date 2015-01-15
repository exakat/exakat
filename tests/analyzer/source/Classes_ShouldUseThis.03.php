<?php

class useThis {
    static public function staticButEmpty() {
        $a++;
    }

    static function staticConstant() {
        static::e;
    }

    static function staticProperty() {
        static::$a;
    }

    static function staticMethod() {
        static::b();
    }

    static function bothpropertyandmethod() {
        static::$c;
        static::d();
    }
}
?>