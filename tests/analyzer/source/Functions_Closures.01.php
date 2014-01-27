<?php

$x = function ($y) { return 2; };


$a = function ($b) use ($x) { return 3; }

?>