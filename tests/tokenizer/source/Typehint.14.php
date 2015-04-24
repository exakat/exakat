<?php

$x = function(array $matches) {return new Match($matches[1], $matches[0]); };
$x = function(callable $matches) {return new Match($matches[1], $matches[0]); };
$x = function(x $matches) {return new Match($matches[1], $matches[0]); };
$x = function(x\d $matches) {return new Match($matches[1], $matches[0]); };
$x = function(\x\d $matches) {return new Match($matches[1], $matches[0]); };
