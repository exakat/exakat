<?php

class x extends y {
    function y () {
        echo parent::class."\n";
        echo self::class."\n";
        echo static::class."\n";
        
    }
}


?>