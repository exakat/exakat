<?php 

function x ($y) { print $y; $y++;}

x($a);
x(&$b);
x(&$c, &$d);
x(&$e, &$f, &$g);

?>
