<?php

// ignore skipping variables
extract($array);

// ignore skipping variables
extract($array, EXTR_SKIP);

// prefix all variables explicitely variables with 'php_'
extract($array, EXTR_PREFIX_ALL, 'php_');

// overwrites explicitely variables
extract($array, EXTR_OVERWRITE);

// overwrites implicitely variables : do we really want that? 
extract($array, EXTR_OVERWRITE);

// Too many values
extract($array, x, 3, 4);
// Too many values
classe::extract($array);

?>