<?php

function cmpUsed ($a, $b) { return true; }
function cmpUsedFullnspath ($a, $b) { return true; }
function cmpNotUsed ($a, $b) { return true; }


array_diff_uassoc(range(1, 10),range(1, 10),range(1, 10),range(1, 10),range(1, 10), 'cmpUsed');
array_uintersect(range(1, 10),range(1, 10),range(1, 10),range(1, 10),range(1, 10), '\\cmpUsedFullnspath');
array_udiff_assoc(range(1, 10),range(1, 10),range(1, 10),range(1, 10),range(1, 10), '\\cmp\\b');

/*
function callableArguments ($a, Callable $b) { var_dump($b); }
Notuasort(range(1,10), 'cmpNot');
callableArguments(1, 'cmp3');
*/
?>