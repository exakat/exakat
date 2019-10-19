<?php

shell_exec('ls -1');
exec('ls -2');
system($x);
proc_open($x, 'ls -3');

`ls -1 $x`;

?>