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

foreach($h as &$i) {
    $i++;
}

?>