<?php

// Ignore
print vsprintf("%04d-%02d-%02d", explode('-', '1988-8-1')); 

// OK (don't find)
print vsprintf("%04d-%02d-%02d", array('1988', '8', '1')); 

// Find
print vsprintf("%04d-%02d-%02d", array('1988', '8')); 
print vsprintf("%04d-%02d-%02d", array('1988', '8', )); 
print vsprintf("%04d-%02d-%02d", array('1988', '8', null)); 
print vsprintf("%04d-%02d-%02d", array('1988', '8', '1', '2')); 

?>