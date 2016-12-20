<?php

interface i {
    function i2();
}

$foo = function ($a) {};

function a() {
    b();
}

function b() {
    a();
}
?>