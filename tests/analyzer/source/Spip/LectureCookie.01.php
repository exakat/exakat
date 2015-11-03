<?php

// written cookie
$_COOKIE = array();

// read cookie
$x = $_COOKIE['read1'];
$x2 = $_COOKIE['read21']['read22'];
$x3 = $_COOKIE['read31']['read32']['read33'];
$x4 = $_COOKIE['read4']++;
join(',', $_COOKIE);

function recuperer_cookies_spip() {
    $x = $_COOKIE['inside_recuperer_cookies_spip'];
}

?>
