<?php

$servers = array_udiff_uassoc('callback3', 'callback',  'callback2');

function callback() {
    return 1;
}

function callback2() {
}

function callback3() {
}

?>