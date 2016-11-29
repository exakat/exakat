<?php

$a = [1,2,3];
// Fast version
$a[] = 4;

$a[] = 5;
$a[] = 6;
$a[] = 7;
$count = count($a);

// Slow version
array_push($a, 4);
$count = array_push($a, 5,6,7);

?>