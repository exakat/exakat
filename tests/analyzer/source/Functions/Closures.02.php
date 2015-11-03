<?php

$x = function () { return 2; };


$a = function () use ($x) { return 3; }

?>