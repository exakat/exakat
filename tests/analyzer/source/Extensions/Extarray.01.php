<?php

$x = range(1,4);
$y = array_walk($x, 'z');
$y = array_run($x);

?>