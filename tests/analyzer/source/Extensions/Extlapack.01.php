<?php

 $a = array(
     array( 1.44,  -7.84,  -4.39,   4.53),
     array(-9.96,  -0.28,  -3.24,   3.83),
     array(-7.55,   3.24,   6.27,  -6.64),
     array( 8.34,   8.09,   5.28,   2.06),
     array( 7.08,   2.52,   0.74,  -2.47),
     array(-5.45,  -5.70,  -1.19,   4.70),
 );

 $b = array(
     array( 8.58,   9.35),
     array( 8.26,  -4.43),
     array( 8.48,  -0.70),
     array(-5.28,  -0.26),
     array( 5.72,  -7.36),
     array( 8.93,  -2.52),           
 );

 $result = Lapack::leastSquaresByFactorisation($a, $b);
 
 // Lapack has no functions.
 lapack();
 ?>
 