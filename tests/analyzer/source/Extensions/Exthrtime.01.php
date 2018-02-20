<?php

$c = new HRTime\Watch;
$c = new HRTime\StopWatch;

$c->start();
/* measure this code block execution */
for ($i = 0; $i < 1024*1024; $i++);
$c->stop();
$elapsed0 = $c->getLastElapsedTime(HRTime\Unit::NANOSECOND);

/* measurement is not running here*/
for ($i = 0; $i < 1024*1024; $i++);

$c->start();
/* measure this code block execution */
for ($i = 0; $i < 1024*1024; $i++);
$c->stop();
$elapsed1 = $c->getLastElapsedTime(HRTime\Unit::NANOSECOND);

$elapsed_total = $c->getElapsedTime(HRTime\Unit::NANOSECOND);

?>