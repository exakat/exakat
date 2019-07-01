<?php

class x {
    static function aSelf(x $z) {
        $z::aSelf();
    }

    static function aStatic(y $z) {
        $z::aStatic();
    }

    static function aX(x $z) {
        $x->aStatic();
    }

    static function aNsname($z) {
        $z::aStatic();
    }
}

?>