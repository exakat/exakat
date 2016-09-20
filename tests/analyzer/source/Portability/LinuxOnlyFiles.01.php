<?php

fopen('/dev/urandom', 'r');
file_get_contents('/proc/meminfo', 'r');
file_get_contents('/proc/stat', 'r');
file_get_contents('/dev/random', 'r');

file_get_contents('/etc/hosts', 'r');
file_get_contents('/etc/group', 'r');
file_get_contents('/etc/passwd', 'r');

file_get_contents('/etc/host', 'r'); // missing s
file_get_contents('/etc/GROUP', 'r');

?>