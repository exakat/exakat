<?php

// KO (2 elseif)
if ($a === 1) 
$a1;
 elseif ($a === 2) 
$a2;
 else 
$a4;

// OK (3 elseif)
if ($a === 1) 
$a1;
 elseif ($a === 2) 
$a2;
 elseif ($a === 3) 
$a3;
 else 
$a4;


// OK (3 elseif)
if ($a === 11) 
$a11;
 elseif ($a === 12) 
$a12;
 elseif ($a === 13) 
$a13;



// Too short (3 elseif)
if ($a === 21) 
$a21;
 elseif ($a === 22) 
$a22;
 else 
$a23;


// OK (4 elseif)
if ($a === 31) 
$a31;
 elseif ($a === 32) 
$a32;
 elseif ($a === 33) 
$a33;
 elseif ($a === 34) 
$a34;
 else 
$a35;

