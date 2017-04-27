<?php

// Test as preg_match_all is not in a sequence
if(preg_match_all('/(a)(d)/', $string, $r)) { /**/ }  

// default behavior
preg_match_all('/(a)(b)/', $string, $r);
$found = '';
foreach($r[1] as $id => $s) {
    $found .= $s.$r[2][$id];
}

// better behavior
preg_match_all('/(a)(c)/', $string, $r, PREG_SET_ORDER);
$found = '';
foreach($r as $s) {
    $found .= $s[1].$s[2];
}


?>