<?php

function foo(): float {}
$a = fn( float|int $x) =>  1;
$a = fn( float|int $x) =>  1;
$a = fn( float|int $x) =>  1;
$a = fn( float|int|x $x) =>  1;
$a = fn( float|int|x|y $x) =>  1;
$a = fn( float|int|x|y|z\a $x) =>  1;
$a = fn( float|int|x|y|z\a|\a\b\c $x) =>  1;
$a = fn( float|int|x|y|z\a|namespace\d\e $x) =>  1;


?>