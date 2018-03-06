<?php

foreach($a as $b) {
    $c[$b] = 1;
}

foreach($a as $b2 => $c2) {
    $c[$b2] = 1;
}

foreach($a as $b3 => $c3) {
    $c[$c3] = 1;
}

foreach($a as $d => $e) {
    $c[$c3][$e] = 1;
}

foreach($a as $d => $e2) {
    $c[$e][$c3] = 1;
}

?>