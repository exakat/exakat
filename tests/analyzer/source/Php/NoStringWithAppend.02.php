<?php

// Wrong
function a() {
    $a = '1';
    $a[] = 2;
}

// OK
function b() {
    $a[] = 2;
    $a = '2';
}



?>
