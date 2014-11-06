<?php

foreach(array(1,2,3,4) as $value) {} // Normal case

foreach(array(1,2,3,4) as &$value) {} // Reference is useless

$x = array(1,2,3,4);
foreach($x as &$value) {} // Reference is useless

?>