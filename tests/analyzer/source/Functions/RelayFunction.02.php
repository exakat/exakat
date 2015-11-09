<?php

function aRelay3($a) {
    return someClass::methodRelay($a);
}

function aRelay4($a) {
    return $a->methodRelay($a);
}

function notARelay4($a) {
    return someClass::methodRelay($a + 1);
}
?>