<?php

$shell = `ls -la`;

exec('ls -la');
shell_exec('ls -la');

popen('ls -la', 'r');

// will fail
fopen('ls -la', 'r');
?>