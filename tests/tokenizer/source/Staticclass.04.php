<?php

class x {
    function y () {
        echo parent::class."\n";
        echo self::class."\n";
        echo static::class."\n";
        
    }
}


?>