<?php

function foo(): float {}
$a = fn(): float|int => 1;
$a = fn(): float|int => 1;
$a = fn(): float|int => 1;
$a = fn(): float|int|x => 1;
$a = fn(): float|int|x|y => 1;
$a = fn(): float|int|x|y|z\a => 1;
$a = fn(): float|int|x|y|z\a|\a\b\c => 1;
$a = fn(): float|int|x|y|z\a|namespace\d\e => 1;


?>