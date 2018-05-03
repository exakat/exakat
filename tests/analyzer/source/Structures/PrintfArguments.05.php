<?php

sprintf(' a %s %s ', ...$a1); 
\sprintf(' a %s %s ', ...$a1); 

// Too many
sprintf(' a %s %s ', $a1, $a3, ...$a2); 

// OK
sprintf(' a %s %s %s', $a1, $a3, ...$a2); 

// Missing
sprintf(' a %s %s ', $a1, $a3, ...$a2, ...$a4); 

?>