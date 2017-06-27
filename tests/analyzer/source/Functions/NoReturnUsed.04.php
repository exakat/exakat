<?php

class x {
    static function fooVoid($a) {
        if ($a) {
            return; 
        }
    }
    
    static function fooReturn($a) {
        if ($a) {
            return 1; 
        }
    }
    
    static function fooNull($a) {
        if ($a) {
            return null; 
        }
    }
    
    
    static function fooVoidInt($a) {
        if ($a) {
            return ; 
        } else {
            return 1;
        }
    }
    
    static function fooVoidVoid($a) {
        if ($a) {
            return ; 
        } else {
            return ;
        }
    }
}

x::fooVoid();
x::fooReturn();
x::fooVoidInt();
x::fooNull();
x::fooVoidVoid();

?>