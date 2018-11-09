<?php 

function output ( $b = '' ) {
    $a = 3;
    
	global $a;
	static $s;
	
	
	$a = 3;
	$s = 4;
}



?>