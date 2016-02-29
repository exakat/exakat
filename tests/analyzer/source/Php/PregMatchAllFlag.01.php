<?php

// Not Actual case (using PREG_SET_ORDER)
preg_match_all('/a1/', $b, $r1, PREG_SET_ORDER);
foreach($r1[0] as $id1 => $x) {
    $r1[1][$id1] = 2;
}

// Not Actual case (using PREG_SET_ORDER)
preg_match_all('/a2/', $b, $r2, \PREG_SET_ORDER);
foreach($r2[0] as $id2 => $x) {
    $r2[1][$id2] = 2;
}

// Explicit case
// Actual case
preg_match_all('/a5/', $b, $r5);
foreach($r6[0] as $id5 => $x) {
    $r5[1][$id5] = 2;
}

// Actual case
preg_match_all('/a6/', $b, $r6);
foreach($r6[0] as $id6 => $x) {
    $r6[1][$id6] = 2;
}

// Actual case
preg_match_all('/a7/', $b, $r7, PREG_PATTERN_ORDER);
foreach($r7[0] as $id7 => $x) {
    $r7[1][$id7] = 2;
}

// Actual case
preg_match_all('/a8/', $b, $r8, \PREG_PATTERN_ORDER);
foreach($r8[0] as $id8 => $x) {
    $r8[1][$id8] = 2;
}