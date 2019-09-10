<?php

$c = explode('a1', $string); 
array_pop($c);

$d = explode('a2', $string); 
array_shift($d);

$e = explode('a3', $string); 
array_pop($f);

list($a, $b, , , , ) = explode('a4', $string); 
list($a, $b, , , ,$c) = explode('a5', $string); 

$g = explode('a6', $string); 
$d = array_slice($g, 0, -1);

$g = explode('a7', $string); 
$d = array_slice($g, 0, 1);

?>