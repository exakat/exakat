<?php

foreach($a as &$b) {
    $b[3]++;
}

foreach($c as &$d) {
    unset($d[3], $d[4], $d[5]);
}

foreach($c2 as &$d2) {
    unset($d[3], $d[4], $d2[5]);
}

foreach($c3 as &$d3) {
    unset($d[3], $d[4], $d2[5]);
}

foreach($c3 as &$d4) {
    print $d4[5];
}

foreach($e as &$f) {
    $f[] = 1;
}

foreach($g as &$h) {
//    (unset) $h;
}

foreach($g as &$i->j) {
//    (unset) $h;
}

?>
