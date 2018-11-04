<?php

$a['b'] = 3; 
$a[b0] = 4; 
 print "$a[b1]\n"; //: b is not a constant! 
 print "{$a[b2]}\n"; // b2 is a constant
 print "${a[b3]}\n"; // b3 is a constant
 print "$a[b4][b5]\n"; // b4 is a string, b5 is nothing
 print "${a['b6']}\n"; // b6 is a string
