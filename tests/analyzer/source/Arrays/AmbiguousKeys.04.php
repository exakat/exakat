<?php

// Still wrong, immediate typecast to 1
$x[1]  = 4; 
$x[1.0]  = 5; 
$x[true] = 6; 
$x[true][3] = 7; 
$x[3][1.1] = 8; 

?>