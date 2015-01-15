<?php

class useThis {
    static public function selfButEmpty() {
        $a++;
    }

    static function selfProperty() {
        self::$a;
    }

    static function selfConstant() {
        self::e;
    }

    static function selfMethod() {
        self::b();
    }

    static function bothpropertyandmethod() {
        self::$c;
        self::d();
    }
}
?>