<?php

foreach($a as $b5) {
    list($c, $d) = $b5;
    
    echo $d;
}

foreach($a as $b4) {
    list($c, $d) = $e;
    
    echo $d;
}

foreach($a as list($b7, $c7)) {
    list($c, $d) = $e;
    
    echo $d;
}

// $b is used as an array with integers. It could be split initially.
foreach($a as $b6) {
    echo $b6[1];
}

// $b is used as an array with strings
foreach($a as $b2) {
    echo $b2['a'];
}

// $b is used as an object
foreach($a as $b3) {
    echo $b->x;
}

?>