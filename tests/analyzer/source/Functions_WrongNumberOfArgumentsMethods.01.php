<?php

swift::ini_set();
other::ini_set();
swift::ini_set_ok();
enough::ini_set(1,2);
tooMany::ini_set(1,2,3,4,5);

$swift->ini_set();
$other->ini_set();
$tooMany->ini_set(1,2,3,4,5);
$enough->ini_set(1,2);
$swift->ini_set_ok();

?>