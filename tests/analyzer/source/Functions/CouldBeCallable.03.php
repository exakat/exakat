<?php

trait t {
    function foo1($callable) {
        array_map($callable, [1,2,3]);
    }
    
    function foo2(callable $callable) {
        array_map($callable, [1,2,3]);
    }
    
    function foo3(string $callable) {
        array_map($callable, [1,2,3]);
    }
    
    function foo4(callable $callable) {
        array_map($callable, [1,2,3]);
    }
    
    function foo5($callable) {
        array_map('a', $callable);
    }
}

?>