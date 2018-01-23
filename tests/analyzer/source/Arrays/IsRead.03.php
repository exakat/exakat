<?php

foreach($a as $b5) {
    list($c, $d) = $b5;
    
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

foreach($a as $b7) {
    $b7[3] = 3;
}

?>