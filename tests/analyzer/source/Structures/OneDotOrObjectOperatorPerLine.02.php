<?php

$d = $a1->p1->b->c;
$d = $a2->p1->b()->c;
$d = $a3->p1->b()->c();
$d = $a4->p1->b->c;

$d = $f1->p
        ->b
        ->c;
$d = $f2->p
        ->b()
        ->c;
$d = $f3->p
        ->b()
        ->c();
$d = $f4->p
        ->b
        ->c;

// That is OK
$e = $a4->b('a' . 'c');

?>
