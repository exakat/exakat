<?php

$a = function () use (&$a) {};

$b = function () use ($b) {};

$c = function ($c) {};

$d = function (&$d) {};

?>