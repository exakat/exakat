<?php

const STRING = 'a';
const FLOAT = 3.3;

// all are here, typehint and default
function foo1(float $a = STRING == 1 ? 'a' : 2.2) {}

// all are here, typehint and default
function foo2(float $a = (STRING == 1 ? 'a' : 2.2)) {}

// all are here, typehint and default
function foo3(float $a = STRING ?: 2.2) {}

// all are here, typehint and default
function foo4(float $a = FLOAT ?: 2.2) {}

?>
