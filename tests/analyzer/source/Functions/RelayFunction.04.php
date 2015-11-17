<?php

function notARelay(stdclass $a = null) {
    return 1;
}

function aRelay(stdclass $a = null) {
    return notARelay($a);
}

function aRelay2(stdclass $a = null) {
    return aRelay($a);
}

function notARelay2(stdclass $a = null) {
    x();
    return aRelay($a);
}

function notARelay3(stdclass $a = null) {
    $b = x($a);
    return aRelay($b);
}

?>