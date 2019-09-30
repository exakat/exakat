<?php

const STRING = 'a';

// Nothing
function foo($a) {}


// only default
function foo1($a = 1) {}

// only typehint
function foo2(int $a) {}

// all are here, typehint and default
function foo3(float $a = STRING) {}

?>
