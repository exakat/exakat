<?php

$hexa = <<<XXX
\x41$a
XXX;

$octo = <<<XXX
\101$b
XXX;
$unicode = <<<XXX
\u{41}$c
XXX;
$normal = <<<XXX
a$d
XXX;

print $hexa;
print $octo;
print $unicode;

?>
