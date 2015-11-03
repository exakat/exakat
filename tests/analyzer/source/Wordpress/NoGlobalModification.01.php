<?php

function x() {
    global $post;
    global $authordata;
    global $noWPglobal, $noWPglobal2;
    
    $post += $authordata;
    $noWPglobal += $noWPglobal2;
}

?>