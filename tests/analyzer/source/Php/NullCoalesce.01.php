<?php

$e = null;

$c = $a ?? '2';

$c2 = $a2 ?? $a22 ?? $a222 ?? '2';

$d = $e ?: '4';

print $c.$d;

var_dump(0 || 2 ?? 3 ? 4 : 5);

