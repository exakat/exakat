<?php

foreach($a as $k => $b5) {
    list($c, $d) = $b5;
    
    echo $d;
}

foreach($a as $k => $b4) {
    list($c, $d) = $e;
    
    echo $d;
}

foreach($a as $k => list($b7, $c7)) {
    list($c, $d) = $e;
    
    echo $d;
}

// $b is used as an array with integers. It could be split initially.
foreach($a as $k => $b6) {
    echo $b6[1];
}

// $b is used as an array with strings
foreach($a as $k => $b2) {
    echo $b2['a'];
}

// $b is used as an object
foreach($a as $k => $b3) {
    echo $b->x;
}

?>