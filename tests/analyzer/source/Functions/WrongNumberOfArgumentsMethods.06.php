<?php

$a = new Swoole\Server();
Swoole\Server::finish();
Swoole\Server::finish(1);
Swoole\Server::finish(1,2);
Swoole\Server::finish(1,2,3);
Swoole\Server::finish(1,2,3,4);
Swoole\Server::finish(1,2,3,4,5);

$b = new stdClass();
$b->finish();
stdClass::finish();

?>