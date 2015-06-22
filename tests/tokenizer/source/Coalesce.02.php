<?php

$x = NULL;
$y = NULL;
$z = 3;
var_dump($x ?? $y ?? $z); 

$x = ["c" => "d"];
var_dump($x["e"] ?? $x["f"] ?? $x["g"]); 

?>
