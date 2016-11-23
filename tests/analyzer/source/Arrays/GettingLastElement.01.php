<?php
    current(array_slice($a, -1));
    array_slice($a, -1)[0];
    $b = array_reverse($a)[0];
    $b = array_pop($a);
    $a[] = $b;
    
    $d= $a[count($a) - 1];

?>