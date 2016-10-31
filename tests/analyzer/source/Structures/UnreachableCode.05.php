<?php

function OK1() {
    // drop the else
    if ($a1) {
        return $a;
    } else {
        doSomething();
    }
}

function OK2() {
    // drop the then
    if ($a2) {
        doSomething();
    } else {
        return $a;
    }
}

function OK3() {
    // no else
    if ($a3) {
        return $a;
    } 
}

function OK4() {
    // return in else and then
    if ($a4) {
        return $a;
    } else {
        $b = doSomething();
        return $b;
    }// Nothing after ifthen
}

function KO1() {
    // return in else and then
    if ($a5) {
        return $a;
    } else {
        $b = doSomething();
        return $b;
    }
    $a++;
}

?>