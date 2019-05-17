<?php

$a = (string) ($b ? 3 : 4);
var_dump($a);
$a = (string) $b ? 3 : 5;
$a = ((string) $b) ? 3 : 6;
$a = (string) ($b) ? 3 : 7;
var_dump($a);
$a = (string) $b ?: 8;
var_dump($a);
$a = (string) $b ?? 9;
var_dump($a);

?>