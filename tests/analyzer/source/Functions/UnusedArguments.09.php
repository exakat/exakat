<?php

    $y = 1;
    $x  = 2;
    
    $fn = fn($x, ...$rest) => $x;
    $fn = static fn($y = 2, ...$rest) => $x;
    $fn = fn($y = 3, ...$rest) => $y;

    $fn = fn($y = 4, &$rest) => $y + $rest;

?>