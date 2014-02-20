<?php 
$number = ($count === 3)
		? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64)
		: (($temp[0] % 32) * 64) + ($temp[1] % 64);

$out .= '&#'.$number.';';

 ?>