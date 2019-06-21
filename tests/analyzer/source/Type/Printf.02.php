<?php

const A = "%'.1d\n";
define('B', "%'.2d\n");

echo sprintf(A, 123);
echo printf(\B, 123);
echo B\sprintf("%'.9d\n", 123);

?>