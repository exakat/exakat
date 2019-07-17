<?php

function foo() {
    if ($a == 1) {
        doSomething();
    } else {
        return;
    }
}

function foo2() {
    if ($a == 11) {
        doSomething();
    } else {
        goto mylabel;
    }
    
    if ($a == 12) {
        throw new \Exception();
    } else {
        doSomething();
    }
    
    mylabel:
     ;
}

?>