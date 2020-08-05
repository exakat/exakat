<?php

// glob uses GLOB_BRACE
$abcFiles = glob($path.'/{a,b,c}*', GLOB_BRACE); 

// avoiding usage of GLOB_BRACE
$abcFiles = array_merge(glob($path.'/a*'), 
                        glob($path.'/b*'), 
                        glob($path.'/c*'), 
                       ); 

?>