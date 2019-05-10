<?php

foreach($a as [$b]) {
    $c += $b[6];
}

foreach($c as [$d]) {
    $c += $e[6];
}

foreach($f as [$g]) {
    $c += $g->h;
}

foreach($i as [$j]) {
    $c += A::$j;
}

?>
