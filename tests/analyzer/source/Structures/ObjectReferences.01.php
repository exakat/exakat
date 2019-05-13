<?php

function x(a &$b, c $d, &$e, &$f) {
    $e->a;
    $f->b();
}

foreach($f as &$g) {
    $g->go();
}

foreach($j as &$k) {
    $k->go;
}

foreach($j2 as $k => &$v2) {
    $v2->go;
}

foreach($h as &$i) {
    $i++;
}

foreach($h2 as $k => &$i2) {
    $i2++;
}

?>