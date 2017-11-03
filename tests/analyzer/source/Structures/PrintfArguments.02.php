<?php

printf(' a %Y ', $a1); 
printf(' a %%s ', $a1); 
printf(' a %%s ', $a1, $a2); 
printf(' a %%s %;', $a1, $a2, $a3); 

sprintf(' a %s ', $a1); 
\sprintf(' a %s ', $a1, $a2); 
sprintf(' a %s ', $a1, $a2, $a3); 

?>