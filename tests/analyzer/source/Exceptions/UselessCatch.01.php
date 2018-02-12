<?php

function foo() {
    try {
        $b = doSomething();
    } catch (Exception $e) { 
        return 1; 
    }
    
    $b->someMore();
}

function foo2() {
    $a = 0;
    try {
        $b = doSomething();
    } catch (Exception $e2) { 
        ++$a;
        return 1; 
    }
    
    $b->someMore();
}

?>