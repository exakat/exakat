<?php

function x() {
    global $onlyInXWithGlobal;
    $GLOBALS['onlyInXWithGlobals']++;
    
    global $inXAndY;
    $GLOBALS['inXAndYWithGlobals'];
    global $inXAndYInverted;
    $GLOBALS['inXAndYInverted2'];

    global $inXAndGlobal;
    $GLOBALS['inXAndGlobal'];

    global $explicitInGlobal;
}

function y() {
    global $inXAndY;
    $GLOBALS['inXAndYWithGlobals']++;

    global $inXAndYWithGlobals;
    $GLOBALS['inXAndYInverted']++;
    global $inXAndYInverted2;
}

global $explicitInGlobal;

$inXAndGlobal;
$GLOBALS['inXAndGlobal'];

?>