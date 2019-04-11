<?php

foreach(array(1,2,3,4) as &$value) {
    $x += $value;
} // Reference is useless

foreach(array(11,12,13,14) as &$modifiedValue) {
    $modifiedValue = 2;
} // Normal case

?>