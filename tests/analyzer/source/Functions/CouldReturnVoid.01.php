<?php

function foo() {
    return;
}

function foo1() {
    ++$a;
}

function foo2() {
    if (++$a) {
        return ;
    } else {
        ++$a;
    }
}

function foo3() {
    if (++$a) {
        return 1;
    } else {
        return ;
    }
    
    return ;
}


?>