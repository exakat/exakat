<?php

class x {
    function foo1($callable) {
        $callable();
    }
    
    function foo2($callable) {
        $this->$callable();
    }
    
    function foo3($calledTwice) {
        $calledTwice();
        $calledTwice();
    }
    
    function foo4(callable $AlreadyCallable2) {
        return $AlreadyCallable;
    }
    
    function foo5($a, $b, $c, $callable) {
        return $callable($a, $b, $c);
    }
}

?>