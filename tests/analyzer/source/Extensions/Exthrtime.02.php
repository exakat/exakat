<?php

namespace hrtime;

$c = new Watch;
$c = new StopWatch;

$c->start();
/* measure this code block execution */
for ($i = 0; $i < 1024*1024; $i++);
$c->stop();
$elapsed0 = $c->getLastElapsedTime(Unit::NANOSECOND);

/* measurement is not running here*/
for ($i = 0; $i < 1024*1024; $i++);

$c->start();
/* measure this code block execution */
for ($i = 0; $i < 1024*1024; $i++);
$c->stop();
$elapsed1 = $c->getLastElapsedTime(Unit::NANOSECOND);

$elapsed_total = $c->getElapsedTime(Unit::NANOSECOND);

?>