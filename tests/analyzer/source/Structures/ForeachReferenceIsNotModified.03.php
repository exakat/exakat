<?php


foreach($a as &$o) {
    $o->m = 1;
}

foreach($a as &$o1) {
    $a += $o1->m;
}

foreach($a as &$b) {
    $b[1] = 1;
}

foreach($a as &$b2) {
    $b2[] = 1;
}

foreach($a as &$b3) {
    $a += $b3[3];
}
?>
