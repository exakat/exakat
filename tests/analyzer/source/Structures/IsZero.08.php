<?php 

$d = 3 - $e = 4;

// Yes
$c += $a['b'] + 4 + $d["br$f"] - $previous["br$g"];
$c += $a->b1 + 1 - $a->b1;
$c += $a->b2 + 1 - ($a->b2);
$c += $a->b3 + 1 - ($d = $a->b3);
$c += $a->b4 + 1 - $d = $a->b4;

// No
$c += $a['b'] + 4 * $d["br$f"] - $previous["br$g"];
$c += $x - $X;
$c += $a->b5 - $a->b6 - $a->b6;

?>