<?php

// Display BC
echo mb_substr('ABC', 1 , 2, 'UTF8');

// Yields Warning: mb_substr() expects parameter 3 to be int, string given
// Display 0 (aka, substring from 0, for length (int) 'UTF8' => 0)
echo mb_substr('ABC', 1 ,'UTF8');

?>