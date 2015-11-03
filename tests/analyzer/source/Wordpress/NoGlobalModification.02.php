<?php

function x() {
    $GLOBALS['post'] += 3;
    $GLOBALS['noWPglobal'] += $noWPglobal2;
    
    $wp_version = $GLOBALS['multipage'];
}

?>