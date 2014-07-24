<?php

swift::version();
other::version();
swift::version_ok();
enough::version(1,2);
tooMany::version(1,2,3,4,5);

$swift->version();
$other->version();
$tooMany->version(1,2,3,4,5);
$enough->version(1,2);
$swift->version_ok();

?>