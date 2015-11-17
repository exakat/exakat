<?php

function notARelay($a = null) {
    return 1;
}

function aRelay($a = null) {
    return notARelay($a);
}

function aRelay2($a = null) {
    return aRelay($a);
}

function notARelay2($a = null) {
    x();
    return aRelay($a);
}

function notARelay3($a = null) {
    $b = x($a);
    return aRelay($b);
}

?>