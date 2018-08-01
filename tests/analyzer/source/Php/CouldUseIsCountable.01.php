<?php

is_array($x1) or $x1 instanceof \Countable ;
$x2 instanceof \Countable || is_array($x2);

$x3 instanceof \Countable xor is_array($x3);

!is_array($arg4) and !$x4 instanceof \Countable;

?>