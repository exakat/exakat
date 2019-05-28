<?php

class x {
    function y() {
        echo x::class;
        echo \x::class;
        echo static::class;
        echo parent::class;
        echo self::class;
        echo b::class;
    }
}

?>