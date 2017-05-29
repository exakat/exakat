<?php

// Same subjects
$a = str_replace($a1, $b1, str_replace($a2, $b2, $c));
$a = str_replace($a1, $b1, str_replace($a2, $b2, str_replace($a3, $b3, $c)));

// Different nesting
$a = str_replace($a1, $b1, file($a2, $b2, $c));
$a = str_replace($a1, $b1, str_ireplace($a2, $b2, preg_replace($a3, $b3, $c)));

?>