<?php

foreach ($a1 as $b) {
    if (!in_array($b, $c)) {
        $c[] = $b;
        }
}

foreach ($a2 as $k => $b) {
    if (in_array($k, $c3)) {
       $d = 3;
    } else {
       $c3[] = $k;
    }
}


foreach ($a3 as $k => $b) {
    if (isset($c4[$k])) {
       $d = 3;
    } else {
       $c[] = $b;
    }
}

?>