<?php

$a = range(0, 10); // integers from 0 to 10

$odds = array_filter(function($x) { return $x % 2; });

// Slow and cumbersome code
$odds = array();
foreach($a as $k => $v) {
    if ($a % 2 == 1) {
        $bColumn[] = $v;
    }
}

// Missing the condition
$odds = array();
foreach($a2 as $k2 => $v2) {
    $bColumn[] = $v2;
}

// doing something else
$odds = array();
foreach($a4 as $k4 => $v4) {
    $bColumn[] = $v4;
}

?>