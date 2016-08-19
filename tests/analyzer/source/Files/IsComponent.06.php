<?php

// $this structures is actually a conditional definition
if (!class_exists('x')) {
    class x {}
}

if (!class_exists('x2')) {
    class x21 {}
    class x22 {}
}

if (!class_exists('x3')) {
    class x31 {}
    class x32 {}
    function () {};
}

if (!defined('x')) {
    define( 'x', 2);
}


?>