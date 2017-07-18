<?php 
$a1 = $b and $c;
$a2 = 8 or $c;
$a3 = foo($d) XOR $c;    
$a4 = (foo($d) AND $c) or $e;    

$b5 = (foo($d) AND $c);    
foo($d) AND ($b5 = $c);    
$b7 = $a->foo($d) && $c;    

?>