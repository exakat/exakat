<?php

$x = 'abc';
preg_match_all('/(?<name>a)/', $x, $r);
print_r($r[1]);
print_r($r['name']);

preg_match('/(?<name>a)(?<sub>b)/', $x, $s);
print $r[2];
print $r['sub'];

