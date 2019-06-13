<?php

const A = ' a %s %s ';

class x {
    const B = ' a %s %s ';
}
printf(A, $a1); 
printf(A, $a1, $a2); 
printf(A, $a1, $a2, $a3); 

sprintf (x::B, $a1); 
\sprintf(x::B, $a1, $a2); 
sprintf (x::B, $a1, $a2, $a3); 

?>