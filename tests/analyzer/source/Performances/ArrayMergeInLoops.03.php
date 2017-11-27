<?php

foreach($a as $b) {
    foreach($c as $d) {
        $b = array_merge($b, $d);
    }
}

do {
    foreach($c2 as $d2) {
        $f = array_merge($f, $b);
    }
} while (1);

do {
    foreach($c3 as $d3) {
        $g = array_merge($f, $b);
    }
} while (1);

?>