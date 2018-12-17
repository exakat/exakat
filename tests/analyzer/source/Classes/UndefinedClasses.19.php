<?php

class x {
    const A = 1;
    
    function y() {
        self::A;
        parent::A;
        static::A;

        SELF::A;
        PARENT::A;
        STATIC::A;
    }
}

$x = new x();
$x->y();
?>