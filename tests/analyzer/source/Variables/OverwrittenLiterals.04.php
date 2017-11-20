<?php

function ($x) {
// Not a problem
for ($i = 0; $i < 10; $i++) {}
for ($i = 0; $i < 10; $i++) {}

// Not a problem
for ($j = 0; $i < 10; $i++) {}
$j = 1;

// Not a problem
for ($k = 0, $l=0; $i < 10; $i++) {}
$l = 1;
$k = 0;

// Not a problem
for ($m = 0, $n=0; $i < 10; $i++) {}

// once
$o = 0;

// twice
$p = 0;
$p = 1;

// three times
$q = 0;
$q = 1;
$q = 1;
}
?>