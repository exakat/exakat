<?php

function f($a, $b) {
    echo $a;
    echo $b;
}

f($PHP_SELF, $php_self);

function f2($a, $b) {
    print($a);
    print($b);
}

f2($HTTP_RAW_POST_DATA, $http_raw_post_data);

function f3($a, $b) {
    echo $a;
    echo $b;
}

f3($_SERVER['DOCUMENT_ROOT'], $_SERVER['QUERY_STRING']);


function f4($a, $b) {
    print($a);
    print($b);
}

f4($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']);



// Those are called with safe functions.

function f5($a, $b) {
    strtolower( $a);
    strtolower( $b);
}

f5($_SERVER, $_SERVER);

function f6($a, $b) {
    strtolower( $a);
    strtolower( $b);
}

f6($_SERVER['DOCUMENT_ROOT'], $_SERVER['GATEWAY_INTERFACE']);

// No function definition
f7($_SERVER['HTTP_HOST'], $_SERVER['HTTP_PORT']);
