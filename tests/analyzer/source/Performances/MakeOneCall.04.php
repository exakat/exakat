<?php

// Same subjects
$c = preg_replace($a1, $b1, $a);
$a = preg_replace($a2, $b2, $c);

// Different subjects
$a = preg_replace($a1, $b1, $c2);
$a = preg_replace($a2, $b2, $c3);

?>