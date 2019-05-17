<?php

echo "sum: " . $a + $b;

echo $a1 + $b1 . "sum: ";

echo "sum: " . $a2 - $b2;

echo $a2 - $b2 . "sum: ";
 
// current behavior: evaluated left-to-right
echo ("sum: " . $a) + $b;
 
// desired behavior: addition and subtraction have a higher precendence
echo "sum :" . ($a + $b);


?>