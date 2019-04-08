<?php
ereg('a', 'b');

split('a', 'b');

if (!function_exists('split')) {
    function split($a, $b) {
    }
}

?>