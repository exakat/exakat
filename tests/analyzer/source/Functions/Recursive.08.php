<?php

use x as Z;

class x {
    static function aSelf() {
        self::aSelf();
    }

    static function aStatic() {
        static::aStatic();
    }

    static function aX() {
        x::aX();
    }

    static function aNsname() {
        \x::aNsname();
    }

    static function aZUse() {
        Z::aZUse();
    }

    static function aY() {
        Y::aStatic();
    }
}

?>