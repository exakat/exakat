<?php

class x {

function foo() {
    $x = static function () {};
    $x = static function ($x) {};
    $x = static function ($g, $f) {};
    $x = static function ($g = 4, $f = 3) {};
}
}

?>