<?php

class x {
    static function foo($a) {
        if ($a > 1) {
            x::foo($a);
        }
    }

    static function bar($a) {
        if ($a > 1) {
            // Not oneself
            $a::bar($a);
        }
    }

    // This is recursive!
    static function bar2(x $a) {
        if ($a > 1) {
            // Not oneself
            $a::bar2($a);
        }
    }

    static function foobar() {
        self::foobar();
    }

}
?>