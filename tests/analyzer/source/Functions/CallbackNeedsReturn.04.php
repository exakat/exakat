<?php

$servers = array_udiff_uassoc($a, 'callback',  array($d, 'c'));
$servers = array_udiff_uassoc($a, 'callback2', array($d, 'c'));

function callback() {
    return 1;
}

function callback2() {
}

?>