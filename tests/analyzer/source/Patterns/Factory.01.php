<?php

function foo() { return new Stdclass(); }

function ($a) { return new Stdclass($a); };

class x {
    function xFoo() { 
        $a = new Stdclass();
        return $a; 
    }

    function xFoo2() { 
        $a = new Stdclass();
        return null; 
    }

    function __get($a) { 
        $a = new Stdclass();
        
        if (rand(0, 1)) {
            return $a; 
        } else {
            return false;
        }
    }
}

?>