<?php

$global = false;
if ( empty( $GLOBALS['global'] ) ) { }

$global2 = false;
if ( empty( $GLOBALS['notGlobal'] ) ) { }

function x() {
    $notGlobal = false;
    if ( empty( $GLOBALS['global2'] ) ) { }
    if ( empty( $GLOBALS['onlyInGlobals'] ) ) { }
}
?>