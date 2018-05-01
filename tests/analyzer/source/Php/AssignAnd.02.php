<?php


$a = $b and die(1);
$a = $c or safeExit();
$a = $c xor other();

function safeExit() {
    die();
}

function other() {
    print 'OK';
}
