<?php
$b = array('c' => 3);
$b2 = array('c2' => 4);
$b3 = 5;
$d = 'D';
$d2 = 'D2';
$d3 = 'D3';

$a = array( 'a' => -$b['c'].$d);
$a2 = array( 'a2' => 2 - $b2['c2'].$d2);
$a3 = array( 'a3' => $d.'3' - $b3.$d3);
print_r($a3);