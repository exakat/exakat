<?php

// Same subjects
$a = str_replace($a1, $b1, str_replace($a2, $b2, $c));
$a = str_replace($a1, $b1, str_replace($a2, $b2, str_replace($a3, $b3, $c)));

// No fullnspath
$a = str_ireplace($a1, $b1, str_replace($a2, $b2, $c));
$a = $str_ireplace($a1, $b1, str_replace($a2, $b2, str_replace($a3, $b3, $c)));

?>