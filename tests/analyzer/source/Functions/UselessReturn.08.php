<?php

// OK
function foo() {
    $a++;
    return 1;
}

// Wrong
function foo2() {
    $a++;
    return ;
}

// OK
function foo3() {
    if ($a++) {
        return ;
    } else {
        return 1;
    }
    return 2;
}

// KO
function foo4() {
    if ($a++) {
        return ;
    } else {
        return 1;
    }
    return ;
}

// OK (Return is not the last
function foo5() {
    if ($a++) {
        return ;
    } else {
        return 1;
    }
}

// OK (No return)
function foo6() {
    $a++;
}
?>