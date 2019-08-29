<?php

$input = 'a<br><BR><i><c>';

const A = '<b'.'r/>';
const B = '<b'.'r/>';

print strip_tags($input, 'br').PHP_EOL;
print strip_tags($input, A).PHP_EOL;
print strip_tags($input,<<<HHH
<br />
HHH
 ).PHP_EOL;
print strip_tags($input, '<B'.'R>').PHP_EOL;

?>