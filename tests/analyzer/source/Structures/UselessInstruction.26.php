<?php

// useless
strtolower('a');
bar($b);

// useful
sort($a);
array_shift($a);
foo($a);


function foo(&$a) {}

function bar($b) {}

?>