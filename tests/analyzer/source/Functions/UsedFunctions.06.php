<?php

function cmpUsed ($a, $b) { return true; }
function cmpUsedFullnspath ($a, $b) { return true; }
function cmpNotUsed ($a, $b) { return true; }


preg_replace_callback($a, 'cmpUsed', $b);
preg_replace_callback($a, '\\cmpUsedFullnspath', $b);
preg_replace_callback($a, '\\cmp\\b', $b);

?>