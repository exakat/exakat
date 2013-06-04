<?php

$file = 'Assignation.01';
$shell = 'cd ../; ./scripts/clean.sh; php ./bin/load -f ./tests/source/'.$file.'.php; php ./bin/analyzer; php ./bin/export -text -o ./tests/exp/'.$file.'.txt';

print shell_exec($shell);

?>