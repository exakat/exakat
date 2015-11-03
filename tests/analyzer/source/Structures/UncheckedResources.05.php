<?php

[10, 20.2, TRUE, NULL, 'hello', (object) NULL, [ [   ] ], fopen(__FILE__, 'r') ];

fread($a->fopen($a));

fread(fopen($file, 'r'), 3);
 
?>