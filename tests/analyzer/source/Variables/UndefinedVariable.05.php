<?php

function with_include($arg) {
    include 'a.php';
    echo $b_include;
}

function with_extract($arg) {
    extract($arg);
    echo $b_extract;
}

function with_eval($arg) {
    eval( $arg );
    echo $b_eval;
}

function actually($arg) {
    foo( $arg );
    echo $b;
}

?>