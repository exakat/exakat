<?php

// Same subjects
$c = str_replace($a1, $b1, $a);
$a = str_replace($a2, $b2, $c);

// Different subjects
$a = str_replace($a1, $b1, $c2);
$a = str_replace($a2, $b2, $c2);

?>