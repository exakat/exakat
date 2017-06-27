<?php

function fooVoid($a) {
    if ($a) {
        return; 
    }
}

fooVoid();

function fooReturn($a) {
    if ($a) {
        return 1; 
    }
}

fooReturn();

function fooNull($a) {
    if ($a) {
        return null; 
    }
}

fooNull();

function fooVoidInt($a) {
    if ($a) {
        return ; 
    } else {
        return 1;
    }
}

fooVoidInt();

function fooVoidVoid($a) {
    if ($a) {
        return ; 
    } else {
        return ;
    }
}

fooVoidVoid();

?>