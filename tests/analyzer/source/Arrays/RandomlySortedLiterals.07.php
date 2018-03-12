<?php

$a = array('F', 'R', 'D', 'E');

// Identical
$a = array('Return', 'Continue', 'Break', 'Exit');
$b = array('Return', 'Continue', 'Break', 'Exit');
$b2 = array('Return', 'Continue', 'Break', 'Exit');

// Identical but the last VOID
$c = array('A', 'B', 'C', 'D', 'E',);
$d = array('A', 'B', 'C', 'D', 'E');

// Randomly sorted
$e = array('A', 'B', 'C', 'D', 'E', 'F',);
$f = array('A', 'B', 'D', 'C', 'E', 'F',);

// Identical but the last VOID
$c = array('A', 'B', 'C', 'D', 'W', 'E',);
$d = array('A', 'C', 'B', 'D', 'W', 'E');

?>