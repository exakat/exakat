<?php


function a_ok(x $a) {}
function a_ok2(\x $a) {}

function a_ko(X $a) {}
function a_ko2(\X $a) {}

class x {
    static function y() { }
    static $z = 1;
    static $za = 1;
}

?>