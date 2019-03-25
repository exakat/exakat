<?php

$a = new Swoole\Server();
$a->finish();
$a->finish(1);
$a->finish(1,2);
$a->finish(1,2,3);
$a->finish(1,2,3,4);
$a->finish(1,2,3,4,5);

$b = new stdClass();
$b->finish();

?>