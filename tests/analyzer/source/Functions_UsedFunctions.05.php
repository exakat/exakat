<?php

function cmpUsed ($a, $b) { return true; }
function cmpUsedFullnspath ($a, $b) { return true; }
function cmpNotUsed ($a, $b) { return true; }


uasort(range(1, 10), 'cmpUsed');
uasort(range(1, 10), '\\cmpUsedFullnspath');
uasort(range(1, 10), '\\cmp\\b');

?>