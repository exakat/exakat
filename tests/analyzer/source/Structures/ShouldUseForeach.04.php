<?php

use function array_shift as b;

while($v = array_pop($a)) { }
while($v = b($a)) { }

do {1;} while($v = array_pop($a));
do {1;} while($v = b($a));

while($v = array_unshift($a)) { };
?>