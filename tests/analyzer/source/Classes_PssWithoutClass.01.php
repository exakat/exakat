<?php

$o = new a4;
a4::m1();

static::m1();
self::m1();
parent::m1();

static::$p1;
self::$p1;
parent::$p1;

static::c1;
self::c1;
parent::c1;

class x {
    function y () {
        static::m2();
        self::m2();
        parent::m2();

        static::$p2;
        self::$p2;
        parent::$p2;

        static::c2;
        self::c2;
        parent::c2;
    }
}
?>