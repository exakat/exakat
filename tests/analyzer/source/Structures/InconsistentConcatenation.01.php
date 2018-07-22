<?php

$a = "b$c".$d;
$a = "b$c$d";

$e = $f." {$c} ";
$e = "$f {$c} ";
$e = "{$f} {$c} ";
$ef = $ff." {$cf[3]} ";
$eg = $f->g." {cg::YES} ";

$eOK = $f.' {$c} ';
$eOK2 = "$f {$c} ";

$a = "b$c".$d.$e;

$a = $f."b$c".$d.$e;

?>