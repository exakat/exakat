<?php

// #0
assert(['a', 'b']);

// #1
uasort($array, [$c, 'd']);

// #2
preg_replace_callback($s, $ss, [$e, 'f']);

// #3
sqlite_create_aggregate($a1, $a2, $a3, [$g, '$h']);

// #4
session_set_save_handler($sssh0, $sssh1, $sssh2, $sssh3, [$sssh4, 'sssh4']);

// #5
session_set_save_handler($sssh0, $sssh1, $sssh2, $sssh3, $sssh4, [$sssh5, 'sssh5']);

// #6
session_set_save_handler($sssh0, $sssh1, $sssh2, $sssh3, $sssh4, $sssh5,[$sssh6, 'sssh6']);

// #7
session_set_save_handler($sssh0, $sssh1, $sssh2, $sssh3, $sssh4, $sssh5, $sssh6, [$sssh7, 'sssh7']);

// 2nd to last arg
array_udiff_uassoc($a1, $a2, $a3, [$i, 'j'], $last);

// last arg
array_udiff($a1, $a2, $a3, [$k, 'l']);

?>