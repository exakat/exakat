<?php

function cmpUsed ($a, $b) { return true; }
function cmpUsedFullnspath ($a, $b) { return true; }
function cmpNotUsed ($a, $b) { return true; }


preg_replace_callback($a, $b, 'cmpUsed');
preg_replace_callback($a, $b, '\\cmpUsedFullnspath');
preg_replace_callback($a, $b, '\\cmp\\b');

?>