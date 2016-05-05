<?php

// Not a double assignation, since it is reprocessed 
$a = someFunction($b);
$a = $a + 1;


// Double assignation, since it is reprocessed 
$a2 = someFunction($b2);
$a2 = $b2 + 1;

?>