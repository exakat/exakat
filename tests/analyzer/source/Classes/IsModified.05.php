<?php

foreach($a as &$b) {
    $b->b++;
}

foreach($c as &$d) {
    unset($d->d, $d3->d3, $d2->d2);
}

foreach($c2 as &$d2) {
    unset($d3->d3, $d3->d3, $d2->d2);
}

foreach($c3 as &$d3) {
    unset($d3->d3, $d3->d3, $d2->d2);
}

foreach($c3 as &$d4) {
    print $d4->d4;
}

foreach($e as &$f) {
    $f->f[] = $f->f2;
}

foreach($g as &$h) {
    (unset) $h->h;
}

?>
