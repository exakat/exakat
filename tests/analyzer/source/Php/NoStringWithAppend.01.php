<?php

// Wrong
function a() {
    $a = '';
    $a[] = 2;
}

// OK
function b() {
    $b = array();
    $b[] = 2;
}

// No initialization : OK
function c() {
    $c[] = 2;
}



?>
