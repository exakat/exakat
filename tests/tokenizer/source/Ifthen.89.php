<?php
$a = 1;
$b = 0;
$c = 3;

if( $a )
{
	if( $b ) 
?>B<?php echo C( 'D', $c) ?>E<?php

	if( $b ) $c++;  echo C( 'D', $c) ?>F<?php
}
function C($a, $b) { echo __METHOD__."\n";}
?>