<?php

$a = 1;
if (empty(foo($a))) {}
if (empty($a->foo($a))) {}
if (empty($foo($a))) {}
if (empty(A::foo($a))) {}
if (empty($a)) {}

if (empty(array())) {}
if (empty([''])) {}

if (empty(${a})) {}

function foo($a) { return true; }
