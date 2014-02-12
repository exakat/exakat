<?php

shell_exec('cd tests/analyzer; phpunit alltests.php > phpunit.txt');
print "OK\n";

?>