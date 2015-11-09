<?php

function notARelay($a) {
    return 1;
}

function aRelay($a) {
    return notARelay($a);
}

function aRelay2($a) {
    return aRelay($a);
}

function notARelay2($a) {
    x();
    return aRelay($a);
}

function notARelay3($a) {
    $b = x($a);
    return aRelay($b);
}

?>