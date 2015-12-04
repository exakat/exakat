<?php

class B {
    static $b = 'C';
    
    static function D(){
        $b = 'E';
        $c = 'F';
        static::$b($c);
        B::$b($c);
    }
    
    static function H($d) {
        echo __METHOD__.'I';
    }

    static function J($d) {
        echo __METHOD__.'I';
    }
}

B::D();
