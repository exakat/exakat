<?php

    $y = 1;
    $x  = 2;
    
    $fn = fn($x, ...$rest) => $x;
    $fn = static fn($y = 2, ...$rest) => $x;
    $fn = fn($y = 3, ...$rest) => $y;

?>