<?php

global $global20;
global $global11a;
$GLOBALS['global11b'] = 2;
$GLOBALS['global02'] = 3;

global $global1b;

function x() {
    global $global20, $global11b;
    $GLOBALS['global11a'] = 1;
    $GLOBALS['global02'] = 3;
    
    $GLOBALS['global1a'] = 3;

}

?>