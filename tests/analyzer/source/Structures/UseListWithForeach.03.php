<?php

$a = array(array('a' => 'c', 'b' => array(1, 2)));
foreach($a as $k => list('a' => $b7, 'b' => $c7)) {
    list($c, $d) = $c7;
    
    echo $d;
}

foreach($a as $k => list('a' => $b7, 'b' => $c8)) {
    echo $c8;
    $d7 = $c8;
    
    echo $d;
}

?>