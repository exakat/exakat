<?php

class x {
    private static $p1 = 1;

    private static $p2 = null;
    private static $p2a = 'a';

    private static $p3 = array();
    private static $p3a = 3;

    private static $p4 ;
    private static $p4a;
    
    function foo() {
        self::$p1 = 1;

        self::$p2 = new c;
        self::$p2->b = 1;

        self::$p2a = new c;
        self::$p2a->b = 1;

        self::$p3['b'] = 1;
        self::$p3a['b'] = 1;

        self::$p4 = new c;
        self::$p4->b = 1;

        self::$p4a = new c;
        self::$p4a = clone $C;
    }

}
?>