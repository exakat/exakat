<?php

// Not a double assignation, since it is reprocessed 
$a = someFunction($b);
$a = strtolower($a);


// Double assignation, since it is reprocessed 
$a2 = someFunction($b2);
$a2 = strtolower($b2);

?>