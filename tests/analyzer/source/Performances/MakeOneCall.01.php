<?php

// Same subjects
$a = preg_replace_callback($a1, $b1, $c);
$a = preg_replace_callback($a2, $b2, $c);

// Different subjects
$a = preg_replace_callback($a1, $b1, $c2);
$a = preg_replace_callback($a2, $b2, $c3);


?>