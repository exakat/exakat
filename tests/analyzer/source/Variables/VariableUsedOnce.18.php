<?php

class B {
    public static function I() {
        self::$g;
        self::$g1[2];

        self::$h->$i->$j;
        
        $g = $g1 + $j;
    }
}

?>