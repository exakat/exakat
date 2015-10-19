<?php
list() = $a;
list(,,) = $a;

// inside list() is wrong. Outside list is OK
list($x, list(), $y) = $a;
list($x, , $y) = $a;

?>