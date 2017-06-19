<?php 

$x = new stdClass();

$y = clone $x;
$z = clone($y);

$z->clone($z);

?>
