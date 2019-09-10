<?php

while($v = array_pop($a)) { }
while($v = array_shift($a)) { }

do {1;} while($v = array_pop($a));
do {1;} while($v = array_shift($a));

while($v = array_unshift($a)) { };
?>