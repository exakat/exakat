<?php

// pow returns INF, as it is equivalent to 1 / 0 ^ 2
$a = pow(0,-2); // 

// exp returns an actual value, but won't be able to represent it as a float
$a = exp(PHP_INT_MAX); 

// 0 ^ -1 is like 1 / 0 but returns INF.
$a = pow(0, -1); 

var_dump(is_infinite($a));
var_dump(classe::is_infinite($staticcall));

// This yields a Division by zero exception
$a = 1 / 0; 

?>