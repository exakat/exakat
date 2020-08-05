<?php

try {

} catch(AvoidThisClass $a) {}

fn ($a) : avoidThisClass => 1;

$x = new class() extends \AvoidThisClass {};

$y = 'Really AvoidThisClass';
?>