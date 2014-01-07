<?php

class A {
    private static $privateStatic = 0;

    private static function f() {
        self::$privateStatic[$key] = "value";
    }
}
?>