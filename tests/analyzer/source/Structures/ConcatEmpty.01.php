<?php

const EMPTY1 = '';
const EMPTY2 = null;
const NOT_EMPTY = 0;

$a = '' . $a;
$b = $b . '';

$c = $c . "$d";

$f .= '';

$e = EMPTY1 . $e;
$e = EMPTY2 . $e;
$e = NOT_EMPTY . $e;

$g = 'a'.$b.''.$c.'E';
?>