<?php

// Same subjects
$c = str_ireplace($a1, $b1, $a);
$a = str_ireplace($a2, $b2, $c);

// Different subjects
$a = str_ireplace($a1, $b1, $c2);
$a = str_ireplace($a2, $b2, $c2);

?>