<?php


if (function_exists('is_countable')) {
    //skip
} else {
    function is_countable($a) {}
}


if (rand()) {

} elseif (function_exists('net_get_interfaces')) {
    function net_get_interfaces() {}
} else {
    // empty
}

// Sorry;..
if (PHP_VERSION < 5) {
    function gmp_kronecker() {}
} 


?>