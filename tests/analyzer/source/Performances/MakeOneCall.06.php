<?php

// Same subjects
$c->d = str_replace($a1, $b1, $a);
$c->d = str_replace($a2, $b2, $c->d);
$e    = str_replace($a3, $b3, $c->d);

// Same subjects, wrong order
$d = str_replace($a4, $b4, $ce);
$e = str_replace($a5, $b5, $ce);

// Different subjects
$a = str_replace($a1, $b1, $c2);
$a = str_replace($a2, $b2, $c2);

?>