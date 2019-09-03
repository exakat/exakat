<?php

$input = 'a<br><BR><i><c>';

print strip_tags($input, 'br').PHP_EOL;
print strip_tags($input, '<br />').PHP_EOL;
print strip_tags($input, '<br/>').PHP_EOL;
print strip_tags($input, '<BR>').PHP_EOL;

?>