<?php

$now = date_create();
usleep(10);              // wait for 0.001 ms
var_dump($now == date_create());
var_dump($now == $a->date_create());

$now = new DateTime();
usleep(10);              // wait for 0.001 ms
var_dump((new DateTime())->format('u') === $now->format('u'));
var_dump((new DateTime())->reFormat('u') === format('u'));
var_dump($now === $h->format('H'));
var_dump($now === H::format('u'));

?>