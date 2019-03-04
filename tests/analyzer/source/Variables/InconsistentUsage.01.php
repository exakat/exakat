<?php
function foo() {
    $a = 1;
    $a[3] = 3;

    $b = 1;
    $b->c = 3;

    $c = 1;
    $c = "d";

    $d = 1;
    $d->e = "d";
    $d['f'] = "d";
}

?>