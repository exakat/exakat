<?php

if (!function_exists('magic_quotes_runtime')) {
    function magic_quotes_runtime($a) {
        return $a;
    }
}

function split($a, $b){}
function splitb($a, $b){}

magic_quotes_runtime();
split();
spliti();
?>